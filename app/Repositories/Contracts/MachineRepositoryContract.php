<?php

namespace App\Repositories\Contracts;

use Carbon\Carbon;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MachineRepositoryContract
 * @package App\Repositories\Contracts
 */
interface MachineRepositoryContract extends RepositoryInterface
{
    public function findMachine($id);

    public function getTotalRed(Carbon $currentDate);

    public function getTotalYellow(Carbon $currentDate);

    public function getTotalGreen(Carbon $currentDate);

    public function getMachinesPeriodMaintenance();
}
