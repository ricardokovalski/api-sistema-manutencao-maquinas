<?php

namespace App\Repositories\Machines;

use App\Entities\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MachineRepositoryContract
 * @package App\Repositories\Machines
 */
class ScheduleRepository extends BaseRepository implements ScheduleRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Schedule::class;
    }
}