<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'file' => 'required|min:1|max:' . 1024 * 150,
            'description' => 'max:1000'
        ]);

        $file = new File();
        $file->resetFile($request->file('file'));
        $file->description = $request->description;
        $file->email = $request->email;
        $file->save();

        Mail::send('emails.file', ['file' => $file], function ($m) use ($request) {
            $m->from(config('mail.from.address'), config('mail.from.name'));
            $m->to($request->email)->subject(Lang::get('mail.file_subject'));
        });

        return response()->json(['success' => true]);
    }

    public function download(string $visitorHash, string $code)
    {
        $file = File::where('visitor_hash', $visitorHash)
            ->where('code', $code)
            ->firstOrFail();

        return response($file->content(), 200)
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . addslashes($file->name) . '"')
            ->header('Expires', '0')
            ->header('Cache-Control', 'must-revalidate')
            ->header('Pragma', 'public');
    }
}
