<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class StartController extends Controller
{
    public function __construct()
    {
    }
    function index()
    {
        return 'ok';
    }

    function create($office = null, $name = null, $phone = null, $address = null)
    {



        $user = optional(User::where('email', $office))->first();



        $data = [
            'user' => $user,
            'name' => $this->cookie('name', $name),
            'phone' => $this->cookie('phone', $phone),
            'address' => $this->cookie('address', $address),
        ];





        return view('orders.create', compact(['data']));
    }
    private function cookie($key, $value)
    {
        $result = $value;

        if (!empty($value)) {
            Cookie::queue($key, $value, 500000);
        } else {
            if (Cookie::get($key) !== null) {
                $result = Cookie::get($key);
            }
        }

        return $result;
    }

    function compose(Request $request)
    {
        $this->validate($request, [
            'officeEmail' => 'required|email',
            'name' => 'required|string',
            'phone' => 'required|min:8|max:22',
            'address' => 'required|min:10|max:80'
        ]);
        $user = optional(User::where('email',  $request->officeEmail))->first();;

        $data = [
            'session' => session()->getId(),
            'user' => $user,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'agentEmail' => $request->agentEmail,
            'distination' => $request->distination

        ];
        return view('orders.compose', compact(['data']));
    }
}
