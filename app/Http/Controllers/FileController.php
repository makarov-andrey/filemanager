<?php

namespace App\Http\Controllers;

use App\File;
use App\TemporaryStorage\TemporaryStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FileController extends Controller
{
    const MIN_FILE_SIZE = 1;
    const MAX_FILE_SIZE = 1024 * 150;

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|min:' . static::MIN_FILE_SIZE . '|max:' . static::MAX_FILE_SIZE,
        ]);

        $code = TemporaryStorage::save($request->file('file'));

        return response()->json(['code' => $code]);
    }

    public function fileProperties(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'fileCode' => 'required|size:64',
            'fileName' => 'required|max:255',
            'description' => 'max:1000'
        ]);

        $file = new File();
        $file->temporaryFileCode = $request->fileCode;
        $file->name = $request->fileName;
        $file->description = $request->description;
        $file->email = $request->email;
        $file->save();

        /*Mail::send('emails.file', ['file' => $file], function ($m) use ($request) {
            $m->from(config('mail.from.address'), config('mail.from.name'));
            $m->to($request->email)->subject(Lang::get('mail.file_subject'));
        });*/

        return response()->json();
    }

    public function destroyTempFile(string $code)
    {
        TemporaryStorage::destroy($code);

        return response()->json();
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
