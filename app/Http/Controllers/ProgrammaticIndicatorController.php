<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use App\Models\Program;
use App\Models\ProgrammaticIndicator;
use App\Models\ProgrammaticIndicatorDisaggregation;
use Illuminate\Http\Request;

class ProgrammaticIndicatorController extends Controller
{
    public function index(){
        $list = ProgrammaticIndicator::all();

        return inertia('prog_indicator/index',[
            'list' => $list
        ]);
    }

    public function create(){
        $programs = Program::all();

        return inertia('prog_indicator/create',[
            'programs'=> $programs
        ]);

    }

    public function store(Request $request){
        
        ProgrammaticIndicator::create([
            'name'       => $request->name,
            'program_id' => $request->program_id,
            'active'     => true,
            'scope'=> $request->scope,
        ]);

        return redirect('/prog-indicators');

    }

    public function edit(string $id){

        $programmaticIndicator = ProgrammaticIndicator::findOrFail($id);
        $programs = Program::all();

        return inertia('prog_indicator/edit',[
            'programmaticIndicator'=> $programmaticIndicator,
            'programs'=> $programs
        ]);

    }

    public function update(Request $request, string $id){
        
        $progIndicator = ProgrammaticIndicator::findOrFail($id);

        $progIndicator->update($request->all());


        return redirect('/prog-indicators');

    }

    public function manageDisaggregations(string $id)
    {
        $indicator = ProgrammaticIndicator::findOrFail($id)->load(['disaggregations']);

        $currentDisaggregations = $indicator->disaggregations->groupBy('group');

        $currentIds = $indicator->disaggregations->pluck('id');

        $otherDisaggregations = Disaggregation::query()
            ->where('active', true)
            ->whereNotIn('id', $currentIds)
            ->get()
            ->map(function ($disaggregation) {
                return [
                    'id' => $disaggregation->id,
                    'name' => $disaggregation->name,
                    'group' => $disaggregation->group,
                    'current' => false,
                    'totalable' => null,
                ];
            })
            ->groupBy('group');

        return inertia('prog_indicator/manage_disaggregations', [
            'indicator' => $indicator,
            'currentDisaggregations' => $currentDisaggregations,
            'otherDisaggregations' => $otherDisaggregations,
        ]);
    }

    public function addDisaggregation(Request $request, string $id)
    {
        ProgrammaticIndicatorDisaggregation::create([
            'programmatic_indicator_id'=> $id,
            'disaggregation_id'=> $request->disaggregation_id,
            'totalable'=> $request->totalable,
        ]);
        
        return back();
    }

    public function removeDisaggregation(string $id)
    {
        ProgrammaticIndicatorDisaggregation::findOrFail( $id)->delete();
        
        return back();
    }

    public function setDisaggregationTotalable(string $id){
        $progDisagg = ProgrammaticIndicatorDisaggregation::findOrFail( $id);
        $progDisagg->update([
            'totalable'=> !$progDisagg->totalable,
        ]);
        
        return back();
    }
    
}
