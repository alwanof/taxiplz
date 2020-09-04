<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $acl = [
            'access_orders_sheet' => (Gate::allows('access_orders_sheet')) ? true : false,
            'access_drivers_map' => (Gate::allows('access_drivers_map')) ? true : false,
        ];
        //$drivers = Driver::latest()->get();
        $geo = [
            'lng' => auth()->user()->getSetting('location.longitude')->value ?? 0,
            'lat' => auth()->user()->getSetting('location.latitude')->value ?? 0,
        ];


        return view('home', compact(['acl', 'geo']));
    }

    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}