<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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


        $limit = (auth()->user()->getSetting('notifications-show-limit')) ? auth()->user()->getSetting('notifications-show-limit')->value : 10;



        $data = [
            'allNoti' => auth()->user()->notifications->take($limit),
            'newNoti' => auth()->user()->unreadNotifications->take($limit),
            'oldNoti' => auth()->user()->readNotifications->take($limit),

        ];


        auth()->user()->unreadNotifications->markAsRead();
        return view('home', compact(['data']));
    }

    public function lang($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}