<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::active()->when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%')->orWhere('last_name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(){
        return view('admin.users.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'user' => 'required|unique:users,user',
            'password' => 'required|min:8'
        ]);

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'user' => $request->user,
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('users.index')->with('message', 'Registro creado');

    }

    public function edit(Request $request, User $user){
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'user' => 'required|unique:users,user,'.$user->id
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('message', 'Registro actualizado');
    }

    public function destroy(Request $request, User $user){
        $user->update(['deleted' => 1]);

        return redirect()->route('users.index')->with('message', 'Registro eliminado');
    }
}
