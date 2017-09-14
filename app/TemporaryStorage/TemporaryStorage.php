<?php

namespace App\TemporaryStorage;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TemporaryStorage
{
    /**
     * Путь до файлов в хранилище
     */
    const STORAGE_PATH = 'temp/';

    /**
     * Время жизни временных файлов
     */
    const TIME_ALIVE = 60 * 60 * 3; // 3 hours

    public static function save(UploadedFile $uploadedFile)
    {
        $code = str_random(64);
        $uploadedFile->storeAs(static::STORAGE_PATH, $code);
        return $code;
    }

    public static function destroy(string $code)
    {
        Storage::delete(static::STORAGE_PATH . $code);
    }

    public static function path(string $code)
    {
        return static::STORAGE_PATH . $code;
    }

    public static function replace(string $code, string $path)
    {
        return Storage::move(static::path($code), $path);
    }

    public static function removeOldFiles()
    {
        $tooOldFiles = array_filter(Storage::files(static::STORAGE_PATH), function($path) {
            $earliestTimeForAlive = time() - static::TIME_ALIVE;
            return Storage::lastModified($path) < $earliestTimeForAlive;
        });
        Storage::delete($tooOldFiles);
    }
}
