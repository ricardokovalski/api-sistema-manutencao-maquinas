<?php

namespace App\Repositories\Maintenance;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\MaintenanceRepositoryContract;
use App\Entities\Maintenance;

/**
 * Class MovementsRepository
 * @package App\Repositories\Movements
 */
class MaintenanceRepository extends BaseRepository implements MaintenanceRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Maintenance::class;
    }

}
