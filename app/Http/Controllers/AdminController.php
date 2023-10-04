<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    const ADMIN = 'admin';

    public function createAdmin()
    {
        return view('admin.admin-register');
    }
    public function storeAdmin(UserRegistrationRequest $request)
    {
        $user = Admin::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => self::ADMIN,
        ]);

        return redirect()->route('login')->with('successMessage', 'Registration successful!');
    }

    public function logout()
    {
        auth()->guard('web')->logout(); // Logout using the 'web' guard
        auth()->guard('admin')->logout(); // Logout using the 'admin' guard

        return redirect()->route('login');
    }

}
