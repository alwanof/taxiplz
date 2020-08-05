<?php

namespace App\Http\Controllers\API;

use App\Configuration;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ConfigurationController extends Controller
{
    public function index()
    {

        return Configuration::latest()->paginate(10);
    }

    public function search(Request $request)
    {
        $configurations = Configuration::where('name', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);

        return $configurations;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'value' => 'required',
        ]);
        try {
            Configuration::create([
                'name' => $request->name,
                'value' => $request->value,
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
            'value' => 'required',
        ]);
        try {
            $configuration = Configuration::findOrFail($id);
            $configuration->name = $request->name;
            $configuration->value = $request->value;
            $configuration->save();

            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    public function destroy($id)
    {
        $config=Configuration::find($id);
        $config->roles()->detach();
        Setting::where('name',$config->name)->delete();
        $config->delete();



        return response()->json(1, 200);
    }

    public function toggleRole(Request $request)
    {

        try {
            $configuration = Configuration::findOrFail($request->configID);
            if ($configuration->roles->contains($request->role)) {
                $configuration->roles()->detach($request->role);
            } else {

                $configuration->roles()->attach($request->role);
            }
            $results = Configuration::find($request->configID)->roles;

            return [
                'pluck' => $results->pluck('id'),
                'data' => $results
            ];
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
