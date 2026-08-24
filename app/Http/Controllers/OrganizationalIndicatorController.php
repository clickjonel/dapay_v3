<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalIndicator;
use Illuminate\Http\Request;

class OrganizationalIndicatorController extends Controller
{
    public function index(){
        $list = OrganizationalIndicator::all();

        return inertia('org_indicator/index',[
            'list' => $list
        ]);
    }

    public function create(){

        return inertia('org_indicator/create');

    }

    public function store(Request $request){
        
        OrganizationalIndicator::create([
            'name'      => $request->name,
            'active'    => true
        ]);

        return redirect('/org-indicators');

    }

    public function edit(string $id){

        $organizationalIndicator = OrganizationalIndicator::findOrFail($id);

        return inertia('org_indicator/edit',[
            'organizationalIndicator'=> $organizationalIndicator
        ]);

    }

    public function update(Request $request, string $id){
        
        $orgIndicator = OrganizationalIndicator::findOrFail($id);

        $orgIndicator->update($request->all());


        return redirect('/org-indicators');

    }

}
