<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|string'
        ]);
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {  
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return redirect()->back()->with('error','Ups! Username atau Password Salah :(');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
