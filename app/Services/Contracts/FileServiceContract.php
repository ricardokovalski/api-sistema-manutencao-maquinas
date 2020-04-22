<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface FileServiceContract
{
    public function storeFile(array $request);

    public function findFile($id);
}
