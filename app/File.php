<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
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
        $this->hash = md5(file_get_contents($this->uploadedFile->getRealPath()));
        $this->name = $uploadedFile->getClientOriginalName();
    }

    /**
     * Получить путь к файлу в хранилище
     *
     * @return string
     */
    public function path()
    {
        return static::STORAGE_PATH . $this->hash;
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
        if ($this->uploadedFile) {
            $this->uploadedFile->storeAs($this->storagePath, $this->hash);
        }

        return parent::save($options);
    }

    public function delete(array $options = [])
    {
        Storage::delete($this->path());

        return parent::delete($options);
    }

    /**
     * Получить ключ маршрута для модели.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hash';
    }
}
