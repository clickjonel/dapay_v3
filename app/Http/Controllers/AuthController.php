<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\PKActivity;
use App\Models\Program;
use App\Models\Province;
use App\Models\Report;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginForm(){
        return inertia('auth/Login',[]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $accessLevel = (int) $user->access_level;
        $data = [];
    
        switch ($accessLevel) {
            // Level 1: Regional / Admin Overview
            case 1:
                $data = Province::query()
                    ->withCount([
                        'barangays',
                        'reports as report_count' => fn ($q) => $q->where('status', 'Approved'),
                        'teams as team_count' => fn ($q) => $q->where('active', 1),
                        'teams as team_pk_kit_count' => fn ($q) => $q->where('pk_kit', 1),
                        'pkActivities',
                        'pkActivities as pk_large_count' => fn ($q) => $q->where('type', 'large'),
                        'pkActivities as pk_small_count' => fn ($q) => $q->where('type', 'small'),
                    ])
                    ->get();
                break;
    
            // Level 2: HRH (Human Resources for Health)
            case 2:
                $data = [
                    'reports' => $user->reports()
                        ->where('status', 'Rejected')
                        ->with(['barangay', 'pkActivity'])
                        ->latest()
                        ->get(),
                    'handledBarangays' => $user->handledBarangays,
                ];
                break;
    
            // Level 3: PDOHO (Provincial DOH Office)
            case 3:
                $data = [
                    'municipalities' => Municipality::query()
                        ->where('province_id', $user->province_id)
                        ->with([
                            'barangays.priorityPrograms',
                            'barangays.team',
                        ])
                        ->withCount([
                            'barangays as pk_sites_count' => fn ($q) => $q->where('pk_site', 1),
                            'barangays as gida_count' => fn ($q) => $q->where('is_gida', 1),
                        ])
                        ->get(),
                ];
                break;
    
            // Level 4: DMO (Development Management Officer)
            case 4:
                // Fetch handled municipalities with nested barangay & team data
                $user->load(['handledMunicipalities.barangays.team', 'handledMunicipalities.barangays.priorityPrograms']);
    
                $data = [
                    'reports' => Report::query()
                        ->whereNull('status')
                        ->whereHas('barangay', fn ($q) => $q->where('province_id', $user->province_id))
                        ->with(['users:id,name', 'barangay:id,name', 'pkActivity:id,name'])
                        ->latest()
                        ->take(10)
                        ->get(),
                    'municipalities' => $user->handledMunicipalities,
                ];
                break;
        }
    
        return inertia('Dashboard', [
            'data' => $data,
        ]);
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect('/');
    }
    
}
