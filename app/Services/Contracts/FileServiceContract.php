<?php

namespace App\Services\Contracts;

interface FileServiceContract
{
    public function storeFile(array $request);

    public function deleteFile($id);
}
