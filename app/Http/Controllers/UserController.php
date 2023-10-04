<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    const USER = 'user';
    //const ADMIN = 'admin';
    public function createUser()
    {
        return view('user.user-register');
    }
    public function storeUser(UserRegistrationRequest $request)
    {

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => self::USER,
        ]);

        return redirect()->route('login')->with('successMessage', 'Registration successful!');
    }

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
        Auth::guard('web')->logout(); // Logout using the 'admin' guard
        return redirect()->route('login');
    }

}
