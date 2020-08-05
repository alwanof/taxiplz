<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function config()
    {
        $roles = Role::all();
        return view('config.config', compact('roles'));
    }

    public function settings()
    {

        return view('config.settings');
    }
}