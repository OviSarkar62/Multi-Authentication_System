<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('user.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Authenticate using the 'web' guard
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->route('dashboard');
        }
        // Authenticate using the 'web' guard
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('dashboardadmin');
        }
        return "Wrong Username or Password!!";
    }

    public function logout()
    {
        if(auth()->guard('web')->check()) {
            auth()->guard('web')->logout(); // Logout using the 'web' guard
        } elseif(auth()->guard('admin')->check()) {
            auth()->guard('admin')->logout(); // Logout using the 'admin' guard
        }
        return redirect()->route('login');
    }
}
