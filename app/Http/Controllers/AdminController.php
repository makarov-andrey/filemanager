<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(CheckAdmin::class);
    }

    public function index()
    {
        return view('admin.index', [
            'files' => File::paginate(30)
        ]);
    }

    public function destroy(File $file)
    {
        $file->delete();

        return redirect()->back()->with('success', Lang::get('file.successful_remove'));;
    }

    public function editFileForm(File $file)
    {
        return view('admin.edit', [
            'file' => $file
        ]);
    }

    public function editFile(File $file, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'max:1000'
        ]);

        $file->name = $request->name;
        $file->description = $request->description;
        $file->save();

        return redirect()->route('admin.index')->with('success', Lang::get('file.successful_save'));
    }
}
