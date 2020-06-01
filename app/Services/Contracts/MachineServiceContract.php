<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface MachineServiceContract
{
    public function storeMachine(Request $request);

    public function assignTechnicalManagerFromMachine(Request $request);

    public function removeTechnicalManagerFromMachine(Request $request);

    public function assignPieceFromMachine(Request $request);

    public function removePieceFromMachine(Request $request);
}
