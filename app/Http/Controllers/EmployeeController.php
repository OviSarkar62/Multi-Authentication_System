<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    const ADMIN = 'admin';

    // Employee Register Form
    public function createEmployee()
    {
        if (is_null($this->user) || !$this->user->can('create.employee')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $roles  = Role::all();
        return view('admin.create-employee',compact('roles'));
    }

    // Employee Register Post and Store
    public function storeEmployee(UserRegistrationRequest $request)
    {
        if (is_null($this->user) || !$this->user->can('store.employee')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }
        // Get the selected role from the request
        $role = $request->roles;
        $roles  = Role::all();

        $employee = Admin::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => self::ADMIN,
            'role' => json_encode($role),
        ]);

        if (count($request->roles)>0) {
                $employee->assignRole($request->roles);
        }

        return redirect()->route('dashboardadmin',compact('roles'))->with('successMessage', 'Employee Registration successful!');
    }

    // Employee List View
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('employee.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $roles = Role::all();
        $employee = Admin::all();
        return view('admin.employee-index', compact('roles'),['admin' => $employee]);
    }

    // Employee Info Edit
    public function editEmployee($id)
    {
        if (is_null($this->user) || !$this->user->can('edit.employee')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }
        // Retrieve the employee with the specified ID from the database
        $employee = Admin::find($id);
        $roles  = Role::all();
        // Pass the employee data to the edit view
        return view('admin.edit-employee', compact('employee','roles'));
    }

    // Employee Info Update
    public function updateEmployee(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('edit.employee')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'roles' => 'required',
        ]);
        $employee = Admin::find($id);
        $roles  = Role::all();
        if (!$employee) {
            return redirect()->route('dashboardadmin')->with('errorMessage', 'Employee not found.');
        }

        // Validate the request data as needed

        // Update the employee's information
        $employee->name = $request['name'];
        $employee->email = $request['email'];
        $employee->role = json_encode($request['roles']);
        // Save the changes
        $employee->save();

        $employee->roles()->detach();
        if (count($request->roles)>0) {
            $employee->assignRole($request->roles);
        }

        return redirect()->route('employee.index')->with('successMessage', 'Employee updated successfully.');
    }


    // Delete Employee
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('delete.employee')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
        }
        // Find the employee by ID
        $employee = Admin::find($id);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        // Check if the logged-in user has permission to delete the employee (e.g., not an admin).
        // Implement your permission logic here.

        // Delete the employee
        $employee->delete();

        return response()->json(['success' => true, 'message' => 'Employee deleted successfully.']);
    }
}
