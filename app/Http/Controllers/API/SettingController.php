<?php

namespace App\Http\Controllers\API;

use App\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Exception;

class SettingController extends Controller
{
    public function index()
    {

        /*$userRole = auth()->user()->roles()->first();
        /*return $userRole->configurations()->paginate(10);*/
        $userRole = auth()->user()->roles()->first();

        $userSettings = $userRole->configurations()->paginate(10);

        $userSettings->transform(function ($item) {
            $item->value = (auth()->user()->settings()->where('name', $item->name)->count() > 0)
                ? auth()->user()->settings()->where('name', $item->name)->first()->value
                : $item->value;
            return $item;
        });
        return $userSettings;
    }

    public function search(Request $request)
    {
        $userRole = auth()->user()->roles()->first();

        return $userRole->configurations()->where('name', 'LIKE', '%' . $request->keywords . '%')
            ->paginate(10);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'value' => 'required',
        ]);
        try {
            if (auth()->user()->settings()->where('name', $request->name)->count() == 0) {
                Setting::create([
                    'name' => $request->name,
                    'value' => $request->value,
                    'user_id' => auth()->user()->id
                ]);
            } else {
                $setting = Setting::where([
                    'name' => $request->name,
                    'user_id' => auth()->user()->id
                ])->first();
                $setting->value = $request->value;
                $setting->save();
            }
            return response()->json(1, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
