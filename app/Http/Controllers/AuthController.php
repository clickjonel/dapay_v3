<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Program;
use App\Models\Province;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginForm(){
        return inertia('auth/Login',[]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Teams
        $teams = Team::selectRaw('
            COUNT(*) as total,
            SUM(pk_kit = 1) as with_kit,
            SUM(pk_kit = 0) as without_kit
        ')
        ->where('active', true)
        ->first();

        // Team members
        $members = TeamMember::selectRaw('
            SUM(pk_oriented = 1) as oriented,
            SUM(pk_oriented = 0) as non_oriented
        ')
        ->first();

        // Users
        $users = User::selectRaw('
            COUNT(*) as total,
            SUM(access_level = 2) as hrh,
            SUM(access_level = 3) as pdoho,
            SUM(access_level = 4) as dmo
        ')
        ->first();

        // Programs
        $programTotal = Program::where('active', true)->count();

        // PK sites per province
        $pkSiteDistributionByProvince = Province::withCount([
            'barangays',
            'barangays as pk_sites_count' => fn ($query) =>
                $query->where('pk_site', true),
        ])
        ->get()
        ->map(fn ($province) => [
            'id' => $province->id,
            'name' => $province->name,
            'total_barangays' => $province->barangays_count,
            'total_pk_sites' => $province->pk_sites_count,
        ]);

        return inertia('Dashboard', [
            'admin' => [
                'program_total' => $programTotal,

                'team_total' => (int) $teams->total,
                'team_with_kit' => (int) $teams->with_kit,
                'team_without_kit' => (int) $teams->without_kit,

                'team_oriented_member' => (int) $members->oriented,
                'team_non_oriented_member' => (int) $members->non_oriented,

                'users' => (int) $users->total,
                'users_hrh' => (int) $users->hrh,
                'users_pdoho' => (int) $users->pdoho,
                'users_dmo' => (int) $users->dmo,

                'pk_site_distribution_by_province' =>
                    $pkSiteDistributionByProvince,
            ],
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
