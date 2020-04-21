<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface MachineServiceContract
{
    public function assignUser(Request $request);

    public function assignPiece(Request $request);
}
