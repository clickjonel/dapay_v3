<?php

namespace App\Http\Controllers;

use App\Models\Disaggregation;
use Illuminate\Http\Request;

class DisaggregationController extends Controller
{
    public function index(){
        $list = Disaggregation::all();

        return inertia('disaggregation/index',[
            'list' => $list
        ]);
    }

    public function create(){

        return inertia('disaggregation/create');

    }

    public function store(Request $request){
        
        Disaggregation::create([
            'name'      => $request->name,
            'active'    => true,
            'group'      => $request->group,
        ]);

        return redirect('/disaggregations');

    }

    public function edit(string $id){

        $disaggregation = Disaggregation::findOrFail($id);

        return inertia('disaggregation/edit',[
            'disaggregation'=> $disaggregation
        ]);

    }

    public function update(Request $request, string $id){
        
        $disaggregation = Disaggregation::findOrFail($id);

        $disaggregation->update($request->all());


        return redirect('/disaggregations');

    }
}
