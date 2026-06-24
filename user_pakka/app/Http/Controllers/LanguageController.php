<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Allowed languages
        $availableLocales = ['en', 'km'];

        // Check if locale is valid
        if (in_array($locale, $availableLocales)) {

            // Store in session
            Session::put('locale', $locale);

            // Set current app locale immediately
            App::setLocale($locale);
        }

        // Redirect back to previous page
        return redirect()->back();
    }
}