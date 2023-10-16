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

    public function userIndex()
    {
        $user = User::all();
        return view('admin.user-index', ['users' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Check if the logged-in user has permission to delete the user.
        // Implement your permission logic here.

        // Delete the user
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }


}
