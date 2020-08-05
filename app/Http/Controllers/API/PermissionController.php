<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Permission::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $permissions = Permission::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);

        return $permissions;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);
        try {
            $permission = Permission::create(['name' => $request->name]);
            $permission->guard_name = 'web';
            $permission->save();
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
            $permission = Permission::findOrFail($id);
            $permission->name = $request->name;
            $permission->save();
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json('$e->getMessage()', 400);
        }
    }


    public function destroy($id)
    {
        Permission::find($id)->delete();

        return response()->json(1, 200);
    }
}