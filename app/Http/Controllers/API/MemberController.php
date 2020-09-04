<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Exception;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->level != 0) {
            return User::where('ref', $user->id)->latest()->paginate(10);
        }
        return User::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        if ($user->level != 0) {
            return User::where('ref', $user->id)->where('name', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('email', 'LIKE', '%' . $request->keywords . '%')
                ->paginate(10);
        }
        return User::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->orWhere('email', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'level' => 'required|min:0|max:2'
        ]);
        try {
            $actor = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(10),
                'level' => $request->level,
                'ref' => auth()->user()->id
            ]);
            switch ($actor->level) {
                case 1:
                    $actor->syncRoles('agent');
                    break;
                case 2:
                    $actor->syncRoles('office');
                    break;
            }
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
            'level' => 'required|min:0|max:2'
        ]);
        $user = auth()->user();
        try {
            $actor = User::findOrFail($id);
            $actor->name = $request->name;
            $actor->email = $request->email;
            if ($actor->level != $request->level) {
                switch ($request->level) {
                    case 1:
                        $actor->syncRoles('agent');
                        break;
                    case 2:
                        $actor->syncRoles('office');
                        break;
                }
                $actor->level = $request->level;
            }

            if (strlen($request->password) > 5) {
                $actor->password = Hash::make($request->password);
            }
            $actor->save();


            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }





    public function destroy($id)
    {

        $user = User::find($id);
        $user->delete();
        return response()->json(1, 200);
    }
}
