<?php

namespace App\Services;

use App\Exceptions\UploadServiceException;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

/**
 * Class UploadService
 * @package App\Services
 */
class UploadService
{
    const SIZE_LIMIT = 5000000;

    protected $uploadFile;
    protected $nameEncrypt;

    public function __construct(UploadedFile $uploadFile)
    {
        $this->uploadFile = $uploadFile;
        $this->make();
    }

    private function make()
    {
        $this->generateNameEncrypt();
    }

    private function generateNameEncrypt()
    {
        $this->nameEncrypt = md5(microtime().$this->getOriginalName());
    }

    public function getOriginalName()
    {
        return $this->uploadFile->getClientOriginalName();
    }

    public function getOriginalExtension()
    {
        return $this->uploadFile->getClientOriginalExtension();
    }

    public function getSize()
    {
        return $this->uploadFile->getSize();
    }

    public function getNameEncrypt()
    {
        return $this->nameEncrypt.'.'.$this->getOriginalExtension();
    }

    public function validate()
    {
        if (in_array($this->getOriginalExtension(), ['doc', 'docx', 'odt', 'txt', 'jpg', 'jpeg', 'png'])) {
            throw new UploadServiceException('Formato inválido. É permitido apenas o formato pdf.', Response::HTTP_NOT_FOUND);
        }

        if ($this->getSize() > self::SIZE_LIMIT) {
            throw new UploadServiceException('Excedeu o tamanho permitido de 5 MB. ', Response::HTTP_NOT_FOUND);
        }
    }

    public function storeFile($path = 'public/')
    {
        $this->uploadFile->storeAs($path, $this->getNameEncrypt(), 'public');
    }
}