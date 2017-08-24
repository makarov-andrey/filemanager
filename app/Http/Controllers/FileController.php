<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class FileController extends Controller
{
    public function form()
    {
        return view('form');
    }

    public function load(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'file' => 'required|max:' . 1024 * 150,
            'description' => 'max:1000'
        ]);

        $file = new File($request->file('file'));
        $file->description = $request->description;
        $file->save();

        return redirect()->back()->with('success', Lang::get('file.success_loading'));
    }
}
