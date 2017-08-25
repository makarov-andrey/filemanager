<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $primaryKey = 'code';

    public $incrementing = false;

    const STORAGE_PATH = 'files/';

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * Ассоциировать экземпляр с файлом из реквеста
     *
     * @param UploadedFile $uploadedFile
     */
    public function associateWithRequestFile(UploadedFile $uploadedFile)
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
    public function getContent()
    {
        return Storage::get($this->path());
    }

    public function save(array $options = [])
    {
        if (empty($this->code)) {
            $this->code = str_random(64);
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
