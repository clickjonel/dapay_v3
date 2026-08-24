<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(){
        $list = Program::all();

        return inertia('program/index',[
            'list' => $list
        ]);
    }

    public function create(){

        return inertia('program/create');

    }

    public function store(Request $request){
        
        Program::create([
            'name'      => $request->name,
            'active'    => true
        ]);

        return redirect('/programs');

    }

    public function edit(string $id){

        $program = Program::findOrFail($id);

        return inertia('program/edit',[
            'program'=> $program
        ]);

    }

    public function update(Request $request, string $id){
        
        $program = Program::findOrFail($id);

        $program->update($request->all());


        return redirect('/programs');

    }
}
