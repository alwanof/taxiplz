<?php

namespace App\Http\Controllers\API;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return User::find(1)->getroles;
        return User::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->orWhere('email', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);

        return $users;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(10),
            ]);
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if (strlen($request->password) > 5) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {

        $user=User::find($id);
        $user->syncRoles([]);
        $user->settings()->delete();
        $user->delete();
        return response()->json(1, 200);
    }

    public function toggleRole(Request $request)
    {
        try {
            $user = User::findOrFail($request->userID);
            if ($user->hasRole($request->role)) {
                $user->removeRole($request->role);
            } else {
                /*$role=Role::where('name',$request->role)->first();
                foreach ($role->configurations as $config){
                    Setting::where([
                        'name'=>$config->name,
                        'user_id'=>$request->userID
                    ])->delete();
                }*/


                $user->syncRoles($request->role);
            }
            return User::find($request->userID)->roles;
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
    public function togglePerm(Request $request)
    {
        try {
            $role = Role::findOrFail($request->roleID);
            if ($role->hasPermissionTo($request->perm)) {
                $role->revokePermissionTo($request->perm);
            } else {

                $role->givePermissionTo($request->perm);
            }
            return Role::find($request->roleID)->permissions;
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
