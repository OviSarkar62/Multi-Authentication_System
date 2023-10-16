<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DashboardAdminController extends Controller

{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('dashboardadmin')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        // }

        return view('dashboardadmin');
    }

}
