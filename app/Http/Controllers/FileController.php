<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        $file = new File();
        $file->associateWithRequestFile($request->file('file'));
        $file->description = $request->description;
        $file->save();

        Mail::send('emails.file', ['file' => $file], function ($m) use ($request) {
            $m->from('hello@filemanager.dev', 'File manager');
            $m->to($request->email)->subject('Your file!');
        });

        return redirect()->back()->with('success', Lang::get('file.success_loading'));
    }

    public function download(File $file)
    {
        $content = Storage::disk('local')->get($file->path());
        return view('download', [
            'file' => $file,
            'content' => $content
        ]);
    }
}
