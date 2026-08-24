<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request){
        $searchKeyword = $request->search;

        $teams = Team::query()
                        ->with(['members','createdBy','barangay.municipality.province'])
                        ->when($searchKeyword, function($query, $keyword){
                            $query->where( "name", "like", "%".$keyword."%");
                        })
                        ->orderBy('name')
                        ->cursorPaginate(15)
                        ->withQueryString();

        return inertia('team/index',[
            'list' => $teams
        ]);
    }

    public function edit(string $id, Request $request)
    {
        $team = Team::findOrFail($id);
        $user = $request->user();
    
        $barangays = Barangay::query()
            ->with(['municipality','province'])
            ->when($user?->province_id, function ($query, $provinceId) {
                $query->where('province_id', $provinceId);
            })
            ->orderBy('name')
            ->get()
            ->map(function($brgy){
                $municipality = $brgy->municipality?->name;
                $province = $brgy->province?->name;
                $fullName = implode(', ', array_filter([$brgy->name, $municipality, $province]));

                return [
                    'id' => $brgy->id,
                    'name' => $fullName,
                ];
            });
    
        return inertia('team/edit', [
            'team' => $team,
            'barangays' => $barangays,
        ]);
    }

    public function update(string $id, Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'active' => 'required|numeric',
            'pk_kit' => 'required|numeric',
            'eo_link' => 'nullable|string',
            'barangay_id' => 'required|numeric|exists:barangays,id'
        ]);

        $team = Team::findOrFail($id);
        $team->update($validated);

        return back();

    }

    public function members(string $id){
        $team = Team::findOrFail($id)->load('members');

        return inertia('team/manage_members',[
            'team'=> $team
        ]);

    }

    public function addMember(Request $request){
        $validated = $request->validate([
            'team_id' => 'required|numeric|exists:teams,id',
            'name' => 'required|string',
            'role' => 'required|string',
            'position' => 'required|string',
            'pk_oriented' => 'required|numeric',
        ]);

        TeamMember::create($validated);

        return back();

    }

    public function deleteMember(string $id){

        TeamMember::find( $id )->delete();

        return back();

    }

    public function updateMember(string $id, Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'position' => 'required|string',
            'pk_oriented' => 'required|numeric',
        ]);

        TeamMember::find( $id )->update($validated);

        return back();

    }

}
