<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $searchKeyword = $request->search;

        $users = User::query()
                        ->with(['province'])
                        ->when($searchKeyword, function($query, $keyword){
                            $query->where( "name", "like", "%".$keyword."%")
                                    ->orWhere( "email", "like", "%".$keyword."%");
                        })
                        ->orderBy('id','desc')
                        ->cursorPaginate(15)
                        ->withQueryString();

        return inertia('user/index',[
            'list' => $users
        ]);
    }

    public function create(){
        $provinces = Province::get();

        return inertia('user/create', [
            'provinces'=> $provinces
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string',
            'password'=> 'nullable|string',
            'province_id'=> 'required|numeric',
            'access_level'=> 'required|numeric',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => '12345',
            'province_id' => $validated['province_id'],
            'access_level' => $validated['access_level'],
        ]);

        return redirect('/users');
    }

    public function edit(string $id){
        $user = User::findOrFail($id);

        $provinces = Province::get();
        $barangays = Barangay::query()
                        ->when($user->id, function($query) use ($user) {
                            $query->where('province_id', $user->province_id);
                        })
                        ->get();

        return inertia('user/edit', [
            'provinces'=> $provinces,
            'barangays' => $barangays,
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id){
        $validated = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string',
            'password'=> 'nullable|string',
            'province_id'=> 'required|numeric',
            'access_level'=> 'required|numeric',
            'handledBarangays' => 'array|min:1'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'province_id' => $validated['province_id'],
            'access_level' => $validated['access_level'],
        ]);

        $user->handledBaragays()->sync($validated['']);

        return back();
    }

}
