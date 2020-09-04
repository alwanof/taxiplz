<?php

namespace App\Http\Controllers;

use App\Notifications\NewUserCreatedDB;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $roles = Role::all();

        $acl = [
            'give_permissions' => (Gate::allows('give-permissions')) ? true : false,
        ];



        return view('users.users', compact(['roles', 'acl']));
    }

    function profile($user)
    {
        try {
            $user = User::findOrFail($user);
            return view('users.profile', compact(['user']));
        } catch (Exception $e) {
            abort(403);
        }
    }
    function me()
    {
        try {
            $user = auth()->user();
            return view('users.profile', compact(['user']));
        } catch (Exception $e) {
            abort(403);
        }
    }

    public function roles()
    {
        return view('users.roles');
    }
    public function permissions()
    {
        return view('users.permissions');
    }




    public function members()
    {
        $levels = [];
        $user = auth()->user();
        switch ($user->level) {
            case 0:
                $levels = [1, 2];
                break;
            case 1:
                $levels = [2];
                break;
        }

        return view('members.index', compact(['levels']));
    }
}