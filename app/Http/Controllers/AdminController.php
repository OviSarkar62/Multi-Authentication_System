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

        $adminRegistered = Admin::where('user_type', self::ADMIN)->exists();

        if ($adminRegistered) {
            return redirect()->route('login')->with('errorMessage', 'An admin is already registered. You cannot register another admin.');
        }

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
}
