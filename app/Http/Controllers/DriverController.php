<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:access_drivers']);
    }
    public function index()
    {
        $acl = ['access_drivers' => (Gate::allows('access_drivers')) ? true : false];
        return view('drivers.index', compact(['acl']));
    }
    function profile(Driver $driver)
    {
        if ($driver->user_id != auth()->user()->id) {
            abort(403);
        }
        try {

            return view('drivers.profile', compact(['driver']));
        } catch (Exception $e) {
            abort(403);
        }
    }
}