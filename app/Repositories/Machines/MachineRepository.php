<?php

namespace App\Repositories\Machines;

use App\Entities\Machine;
use App\Repositories\Contracts\MachineRepositoryContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MachineRepositoryContract
 * @package App\Repositories\Machines
 */
class MachineRepository extends BaseRepository implements MachineRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Machine::class;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findMachine($id)
    {
        return $this->findByField('id', $id)->first();
    }

    /**
     * @param Carbon $currentDate
     * @return mixed
     */
    public function getTotalRed(Carbon $currentDate)
    {
        if (env('DB_CONNECTION') === 'mysql') {
            return $this->model->where(function(Builder $query) use ($currentDate) {
                $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                )) > machines.review_period', [$currentDate->toDateString()]);
            })->count();
        }

        return $this->model->where(function(Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > machines.review_period', [$currentDate->toDateString()]);
        })->count();
    }

    /**
     * @param Carbon $currentDate
     * @return mixed
     */
    public function getTotalYellow(Carbon $currentDate)
    {
        if (env('DB_CONNECTION') === 'mysql') {
            return $this->model->where(function (Builder $query) use ($currentDate) {
                $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) > (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]
                )->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) < machines.review_period', [$currentDate->toDateString()]
                );
            })->count();
        }

        return $this->model->where(function (Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]
            )->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                < machines.review_period', [$currentDate->toDateString()]
            );
        })->count();
    }

    /**
     * @param Carbon $currentDate
     * @return mixed
     */
    public function getTotalGreen(Carbon $currentDate)
    {
        if (env('DB_CONNECTION') === 'mysql') {
            return $this->model->where(function(Builder $query) use ($currentDate) {
                $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                )) < (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]);
            })->count();
        }

        return $this->model->where(function(Builder $query) use ($currentDate) {
            $query->whereRaw('(? - (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id)) 
                    < (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]);
        })->count();
    }

    public function getMachinesPeriodMaintenance()
    {
        $currentDate = Carbon::now();

        if (env('DB_CONNECTION') === 'mysql') {
            return $this->model->where(function (Builder $query) use ($currentDate) {
                $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) > (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]
                )->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) < machines.review_period', [$currentDate->toDateString()]
                );
            })->get();
        }

        return $this->model->where(function (Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > (machines.review_period - machines.warning_period)', [$currentDate->toDateString()]
            )->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                < machines.review_period', [$currentDate->toDateString()]
            );
        })->get();
    }
}
