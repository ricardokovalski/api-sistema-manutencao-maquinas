<?php

namespace App\Services\Contracts;

use App\Entities\Maintenance;

interface MaintenanceServiceContract
{
    public function assignPieceFromMaintenance(Maintenance $maintenance, array $request);
}
