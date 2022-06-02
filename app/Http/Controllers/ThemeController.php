<?php

namespace App\Http\Controllers;
use App\User;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //change theme for Admin/company
        request()->validate([
            'name'=>'required'
        ]);
        Auth::user()->companies()->update(['theme'=>request('name')]);
        return redirect('/home');
    }
}
