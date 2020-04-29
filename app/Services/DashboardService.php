<?php

namespace App\Services;

use App\Repositories\Contracts\MachineRepositoryContract;
use Carbon\Carbon;

/**
 * Class DashboardService
 * @package App\Services
 */
class DashboardService
{
    /**
     * @var MachineRepositoryContract
     */
    protected $machineRepository;

    /**
     * DashboardService constructor.
     * @param MachineRepositoryContract $machineRepository
     */
    public function __construct(
        MachineRepositoryContract $machineRepository
    ) {
        $this->machineRepository = $machineRepository;
    }

    public function getRed()
    {
        return $this->machineRepository->getTotalRed(Carbon::now());
    }

    public function getYellow()
    {
        return $this->machineRepository->getTotalYellow(Carbon::now());
    }

    public function getGreen()
    {
        return $this->machineRepository->getTotalGreen(Carbon::now());
    }
}