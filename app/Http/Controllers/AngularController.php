<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AngularController extends Controller
{
    public function entryPoint ()
    {
        return view('app');
    }
}
