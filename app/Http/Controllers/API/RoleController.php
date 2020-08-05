<?php

namespace App\Http\Controllers\API;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $roles = Role::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);

        return $roles;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        try {
            $role = Role::create(['name' => $request->name]);
            $role->guard_name = 'web';
            $role->save();
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json('$e->getMessage()', 400);
        }
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json('$e->getMessage()', 400);
        }
    }


    public function destroy($id)
    {
        $role=Role::find($id);

        foreach ($role->users as $user){
            $user->settings()->delete();
        }
        $role->configurations()->detach();
        $role->delete();


        return response()->json(1, 200);
    }
}
