<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    private $language;

    public function index($language)
    {
        App::setLocale($language);
        session()->put('locale', $language);
        return redirect()->back();
    }
}
