<?php

namespace App\Http\Controllers\API;

use App\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Driver::where('user_id', auth()->user()->id)->paginate(25);
    }


    public function search(Request $request)
    {
        $permissions = Driver::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->orWhere('email', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);

        return $permissions;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:drivers',
            'pass' => 'required|min:6|max:20',
            'phone' => 'required|min:8|max:20',
            'taxiCode' => 'required|min:2|max:20',
            'taxi' => 'required|min:3|max:20',
            'taxiNo' => 'required|min:3|max:20',
            'taxiColor' => 'required|min:2|max:10',
        ]);
        try {
            Driver::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'pass' => $request->pass,
                'taxiCode' => $request->taxiCode,
                'taxi' => $request->taxi,
                'taxiNo' => $request->taxiNo,
                'taxiColor' => $request->taxiColor,
                'user_id' => auth()->user()->id
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
            'phone' => 'required|min:8|max:20',
            'taxiCode' => 'required|min:2|max:20',
            'taxi' => 'required|min:3|max:20',
            'taxiNo' => 'required|min:3|max:20',
            'taxiColor' => 'required|min:2|max:10',
        ]);
        try {
            $driver = Driver::findOrFail($id);
            $driver->name = $request->name;
            $driver->email = $request->email;
            $driver->phone = $request->phone;
            $driver->taxiCode = $request->taxiCode;
            $driver->taxi = $request->taxi;
            $driver->taxiNo = $request->taxiNo;
            $driver->taxiColor = $request->taxiColor;
            $driver->user_id = auth()->user()->id;
            if (strlen($request->pass) > 5) {
                $driver->pass = $request->pass;
            }
            $driver->save();
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {
        Driver::find($id)->delete();

        return response()->json(1, 200);
    }

    public function active(Request $request)
    {
        try {
            $driver = Driver::findOrFail($request->driver);
            $driver->pn = $request->pn;
            $driver->save();
            return response()->json($driver, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function check($driver)
    {
        $driver = Driver::findOrFail($driver);
        return (strlen($driver->pn) > 8) ? true : false;
    }
}