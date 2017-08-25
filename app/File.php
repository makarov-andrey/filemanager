<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $primaryKey = 'code';

    public $incrementing = false;

    /**
     * Путь до файлов в хранилище
     */
    const STORAGE_PATH = 'files/';

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * переназначить файл
     *
     * @param UploadedFile $uploadedFile
     */
    public function resetFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
        $this->name = $uploadedFile->getClientOriginalName();
    }

    /**
     * Получить путь к файлу в хранилище
     *
     * @return string
     */
    public function path()
    {
        return static::STORAGE_PATH . $this->code;
    }

    /**
     * получить контент файла
     *
     * @return mixed
     */
    public function content()
    {
        return Storage::get($this->path());
    }

    /**
     * Возвращает ссылку для скачивания файла
     *
     * @return string
     */
    public function downloadLink()
    {
        return route('file.download', [$this->visitor_hash, $this->code]);
    }

    public function save(array $options = [])
    {
        if (empty($this->code)) {
            $this->code = str_random(64);
        }
        if (empty($this->visitor_hash)) {
            $this->visitor_hash = md5(session()->getId());
        }
        if ($this->uploadedFile) {
            $this->uploadedFile->storeAs(static::STORAGE_PATH, $this->code);
        }

        return parent::save($options);
    }

    public function delete(array $options = [])
    {
        Storage::delete($this->path());

        return parent::delete($options);
    }
}
