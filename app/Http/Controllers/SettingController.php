<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $setting = Setting::where('user_id', $user->id)->first();
        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $sources = $request->input('sources');
        $categories = $request->input('categories');
        $authors = $request->input('authors');
        $setting = Setting::where('user_id', $user->id)->first();
        if (!$setting) {
            $setting = new Setting;
            $setting->user_id = $user->id;
        }
        $setting->sources = $sources;
        $setting->categories = $categories;
        $setting->authors = $authors;
        $setting->save();
        return response()->json($setting);
    }
}
