<?php

namespace App\Services;

use App\Repositories\Contracts\MachineRepositoryContract;
use App\Services\Contracts\DashboardServiceContract;
use Carbon\Carbon;

/**
 * Class DashboardService
 * @package App\Services
 */
class DashboardService implements DashboardServiceContract
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

    /**
     * @return mixed
     */
    public function getRed()
    {
        return $this->machineRepository->getTotalRed(Carbon::now());
    }

    /**
     * @return mixed
     */
    public function getYellow()
    {
        return $this->machineRepository->getTotalYellow(Carbon::now());
    }

    /**
     * @return mixed
     */
    public function getGreen()
    {
        return $this->machineRepository->getTotalGreen(Carbon::now());
    }
}