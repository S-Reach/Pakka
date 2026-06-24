<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReadingPreference;

class ReadingPreferenceController extends Controller
{
    public function index()
    {
        $preferences = ReadingPreference::firstOrCreate(
            [
                'user_id' => Auth::id()
            ],
            [
                'theme' => 'light',
                'font' => 'sans',
                'khmer_font' => 'battambang',
                'size' => 16
            ]
        );

        return view('preferences', compact('preferences'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'theme' => 'required',
            'size' => 'required|integer|min:12|max:30'
        ]);

        $preference = ReadingPreference::firstOrCreate(
            ['user_id' => Auth::id()]
        );

        $preference->theme = $request->theme;
        $preference->size = $request->size;

        if ($request->filled('font')) {
            $preference->font = $request->font;
        }

        if ($request->filled('khmer_font')) {
            $preference->khmer_font = $request->khmer_font;
        }

        $preference->save();

        return back()->with('success', 'Preferences updated successfully');
    }
}