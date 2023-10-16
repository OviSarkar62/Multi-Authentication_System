<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;



class RolesController extends Controller
{

    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }

        $roles = Role::all();
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('create.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        $roles = Role::all();
        $all_permissions = Permission::all();
        $permission_groups = Admin::getpermissionGroups();
        //dd($permission_groups);
        return view('roles.add-role', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('store.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }
        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        // Process Data
        $role = Role::create(['name' => $request->name,'guard_name' => 'admin']);

        // $role = DB::table('roles')->where('name', $request->name)->first();
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles')->with('success', 'Role added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (is_null($this->user) || !$this->user->can('edit.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }
        $role = Role::findById($id,'admin');
        $all_permissions = Permission::all();
        $permission_groups = Admin::getpermissionGroups();
        return view('roles.edit-role', compact('role', 'all_permissions', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
     {
        if (is_null($this->user) || !$this->user->can('edit.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }
        // Validation Data
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id,
            'permissions' => 'array', // Make sure 'permissions' is an array
        ], [
            'name.required' => 'Please provide a role name',
        ]);
        // Find the role by ID
        $role = Role::findById($id, 'admin');

        // Update the role name
        $role->name = $request->input('name');
        $role->save();

        // Sync the role's permissions
        $permissions = $request->input('permissions', []); // Use an empty array if 'permissions' is not provided
        $role->syncPermissions($permissions);

        session()->flash('success', 'Role has been updated !!');
        return redirect()->route('admin.roles')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('delete.roles')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any role !');
        }
        $role = Role::findById($id,'admin');
        if (!is_null($role)) {
            $role->delete();
        }

        session()->flash('success', 'Role has been deleted !!');
        return redirect()->route('admin.roles')->with('success', 'Role deleted successfully');
    }
}
