<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthAdminController extends Controller
{
    public function login(){
        return view('admin.auth.login');
    }

    public function check(Request $request){
        $credentials = $request->validate([
            'user' => 'required',
            'password' => 'required|min:8'
        ]);

        if(Auth::guard('admin')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'user' => 'Usuario o contraseña incorrecta'
        ]);


    }

    public function logout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard');
    }
}
