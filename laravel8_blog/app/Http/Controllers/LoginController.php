<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['authenticate', 'logout']);
    }

    public function index()
    {
        return view('layouts.auth.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $rules = [
            'email' => 'required|email:dns',
            'password' => 'required'
        ];

        $messages = [
            'email.required' => 'email tidak boleh kosong',
            'email.email' => 'format email salah',
            'password.required' => 'password tidak boleh kosong'
        ];

        $credentials = $this->validate($request, $rules, $messages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->with(
            'loginError',
            'Login Failed.'
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/home');
    }
}
