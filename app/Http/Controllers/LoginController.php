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
        // return "berhasil";

            $request->session()->regenerate();

            // dd(Auth::user(), Auth::Guest());
            // return Auth::user();

            return redirect()->intended('/');
        }

        return redirect()->back()->with('x','Ups! \n Username atau Password Salah :(');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
