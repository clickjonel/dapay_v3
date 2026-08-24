<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\BarangayOrganizationalIndicator;
use App\Models\BarangayPriorityProgram;
use App\Models\Municipality;
use App\Models\OrganizationalIndicator;
use App\Models\Program;
use App\Models\Province;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function index(Request $request){
        $searchKeyword = $request->search;

        $barangays = Barangay::query()
                        ->with(['province','municipality'])
                        ->when($searchKeyword, function($query, $keyword){
                            $query->where( "name", "like", "%".$keyword."%");
                        })
                        ->orderBy('name')
                        ->cursorPaginate(15)
                        ->withQueryString();

        return inertia('barangay/index',[
            'list' => $barangays
        ]);
    }

    public function edit(string $id){
        $barangay = Barangay::findOrFail($id);
        $provinces = Province::all();
        $municipalities = Municipality::all();

        return inertia('barangay/edit',[
            'barangay'=> $barangay,
            'provinces'=> $provinces,
            'municipalities'=> $municipalities
        ]);
    }

    public function update(Request $request, string $id){
        $barangay = Barangay::findOrFail($id);
        $barangay->update($request->all());
        
        return back();
    }

    public function manageOrganizationalIndicators(string $id){
        $barangay = Barangay::with('organizationalIndicators')->findOrFail($id);
        $currentIndicators = $barangay->organizationalIndicators->keyBy('id');

        $indicators = OrganizationalIndicator::query()
            ->get()
            ->map(function ($indicator) use ($currentIndicators) {
                $current = $currentIndicators->get($indicator->id);

                return [
                    'id' => $indicator->id,
                    'name' => $indicator->name,
                    'pivot_id' => $current?->pivot?->id,
                    'total' => $current?->pivot?->total,
                    'community_based' => $current?->pivot?->community_based,
                ];
            });

        return inertia('barangay/manage_barangay_indicators', [
            'barangay' => $barangay,
            'indicators' => $indicators,
        ]);
    }

    public function saveOrganizationalIndicators(Request $request, string $id)
    {
        $barangay = Barangay::findOrFail($id);
    
        $validated = $request->validate([
            'indicators' => ['required', 'array'],
            'indicators.*.id' => ['required', 'exists:organizational_indicators,id'],
            'indicators.*.pivot_id' => ['nullable', 'integer'],
            'indicators.*.total' => ['nullable', 'integer', 'min:0'],
            'indicators.*.community_based' => ['nullable', 'integer', 'min:0'],
        ]);
    
        foreach ($validated['indicators'] as $ind) {
            BarangayOrganizationalIndicator::updateOrCreate(
                [
                    'id' => $ind['pivot_id'],
                ],
                [
                    'barangay_id' => $barangay->id,
                    'organizational_indicator_id' => $ind['id'],
                    'total' => $ind['total'],
                    'community_based' => $ind['community_based'],
                ]
            );
        }
    
        return back();
    }

    public function managePriorityPrograms(string $id){
        $barangay = Barangay::with(['priorityPrograms' => function ($query) {
            $query->orderByPivot('order');
        }])->findOrFail($id);
        
        $currentPriorityPrograms = $barangay->priorityPrograms->keyBy('id');

        $programs = Program::get()->map(function($program) use ($currentPriorityPrograms){
            $current = $currentPriorityPrograms->get($program->id);

            return [
                'id' => $program->id,
                'name' => $program->name,
                'pivot_id' => $current?->pivot?->id,
                'target' => $current?->pivot?->target,
            ];
        });

        return inertia('barangay/manage_barangay_priority_programs', [
            'barangay' => $barangay,
            'programs' => $programs,
        ]);

    }

    public function addPriorityProgram(Request $request, string $id){
        BarangayPriorityProgram::create([
           'target' => $request->target,
           'barangay_id' => $id,
           'program_id'=> $request->program_id,
           'order' => null
        ]);

        return back();
    }

    public function removePriorityProgram(Request $request, string $id){
        BarangayPriorityProgram::findOrFail($request->pivot_id)->delete();

        return back();
    }

    public function updatePriorityProgram(Request $request, string $id){
        BarangayPriorityProgram::findOrFail($request->pivot_id)->update([
            'target'=> $request->target
        ]);

        return back();
    }

    public function savePriorityProgramsOrder(Request $request, string $id){
        foreach($request->order as $prio){
            BarangayPriorityProgram::findOrFail($prio['pivot_id'])->update([
                'order'=> $prio['order'],
            ]);
        }

        return back();
    }


}
