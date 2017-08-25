<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $storagePath = 'files/';

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    public function associateWithRequestFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
        $this->hash = md5(file_get_contents($this->uploadedFile->getRealPath()));
        $this->name = $uploadedFile->getClientOriginalName();
    }

    public function save(array $options = [])
    {
        $this->uploadedFile->storeAs($this->storagePath, $this->hash);

        return parent::save($options);
    }

    public function path()
    {
        return $this->storagePath . $this->hash;
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
