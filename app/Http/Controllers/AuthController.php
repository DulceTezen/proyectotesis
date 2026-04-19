<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function check(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if(Auth::guard('web')->attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended(route('index'));
        }

        return back()->withErrors([
            'email' => 'Correo electrónico o contraseña incorrecta'
        ]);


    }

    public function register(){
        return view('auth.register');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required|unique:clients,document',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|min:8'
        ]);

        Client::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'document' => $request->document,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password) 
        ]);

        return redirect()->route('auth.login');
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}
