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




    public function testo()
    {
        //https://docs.spatie.be/laravel-permission/v3/basic-usage/basic-usage/
        $user = User::find(3);
        $newUser = User::find(5);
        $users = User::with('Settings')->where('id', '!=', $newUser->id)->where('id', '!=', $user->id)->get();
        $users_collections = [];
        foreach ($users as $user) {
            if ($user->settings()->where('name', 'receive-noti-4new-users')->first()) {
                if ($user->settings()->where('name', 'receive-noti-4new-users')->first()->value == 1) {
                    $users_collections[] = $user;
                }
            }
        }



        Notification::send($users_collections, new NewUserCreatedDB($user, $newUser));

        //return auth()->user()->getSetting('notifications-show-limit');



        //event(new UserHasRemoved($user));
    }
}