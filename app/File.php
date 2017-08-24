<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class File extends Model
{
    protected $uploadedFile;

    public function __construct(UploadedFile $uploadedFile, array $attributes = [])
    {
        $this->uploadedFile = $uploadedFile;

        $this->hash = md5(file_get_contents($uploadedFile->getRealPath()));
        $this->name = $uploadedFile->getClientOriginalName();

        parent::__construct($attributes);
    }

    public function save(array $options = [])
    {
        $this->uploadedFile->store('files/' . $this->hash);

        return parent::save($options);
    }
}
