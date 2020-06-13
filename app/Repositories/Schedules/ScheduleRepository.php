<?php

namespace App\Repositories\Schedules;

use App\Entities\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ScheduleRepository
 * @package App\Repositories\Schedules
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