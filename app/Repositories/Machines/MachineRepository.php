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
                return $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) > (datediff((select MIN(machine_schedules.date) 
                    from machine_schedules 
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1), ?))', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
            })->count();
        }

        return $this->model->where(function(Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > (? - (select MIN(machine_schedules.date::TIMESTAMP::DATE) 
                from machine_schedules 
                where machine_schedules.machine_id = machines.id and 
                machine_schedules.status = 1))', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
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
                    ))) > ((datediff((
                    select MIN(machine_schedules.date)
                    from machine_schedules
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1
                    ), ?) - machines.warning_period)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ])->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) < datediff((select MIN(machine_schedules.date)
                    from machine_schedules
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1), ?)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
            })->count();
        }

        return $this->model->where(function (Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > (((select MIN(machine_schedules.date::TIMESTAMP::DATE)
                from machine_schedules
                where machine_schedules.machine_id = machines.id and 
                machine_schedules.status = 1
                ) - ?) - machines.warning_period)', [
                $currentDate->toDateString(), $currentDate->toDateString()
            ])->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                < ((select MIN(machine_schedules.date::TIMESTAMP::DATE)
                from machine_schedules
                where machine_schedules.machine_id = machines.id and 
                machine_schedules.status = 1) - ?)', [
                $currentDate->toDateString(), $currentDate->toDateString()
            ]);
        })->count();
    }

    /**
     * @param Carbon $currentDate
     * @return mixed
     */
    public function getTotalGreen(Carbon $currentDate)
    {
       if (env('DB_CONNECTION') === 'mysql') {
            return $this->model->leftJoin('maintenance', function ($join) {
                return $join->on('maintenance.machine_id', '=', 'machines.id');
            })->where(function(Builder $query) {
                return $query->whereNull('maintenance.machine_id');
            })->orWhere(function(Builder $query) use ($currentDate) {
                $query->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) < ((datediff((select MIN(machine_schedules.date)
                    from machine_schedules
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1), ?)) - machines.warning_period)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
            })->count();
        }

        return $this->model->leftJoin('maintenance', function ($join) {
                return $join->on('maintenance.machine_id', '=', 'machines.id');
            })->where(function(Builder $query) {
                return $query->whereNull('maintenance.machine_id');
            })->orWhere(function(Builder $query) use ($currentDate) {
                $query->whereRaw('(? - (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id)) 
                    < (((select MIN(machine_schedules.date::TIMESTAMP::DATE)
                    from machine_schedules
                    where machine_schedules.machine_id = machine_schedules.id and 
                    machine_schedules.status = 1) - ?) - machines.warning_period)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
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
                    ))) > ((datediff((
                    select MIN(machine_schedules.date)
                    from machine_schedules
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1
                    ), ?) - machines.warning_period)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ])->whereRaw('datediff(?, (
                    select MAX(maintenance.review_at)
                    from maintenance
                    where maintenance.machine_id = machines.id
                    )) < datediff((select MIN(machine_schedules.date)
                    from machine_schedules
                    where machine_schedules.machine_id = machines.id and 
                    machine_schedules.status = 1), ?)', [
                    $currentDate->toDateString(), $currentDate->toDateString()
                ]);
            })->get();
        }

        return $this->model->where(function (Builder $query) use ($currentDate) {
            $query->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                > (((select MIN(machine_schedules.date::TIMESTAMP::DATE)
                from machine_schedules
                where machine_schedules.machine_id = machines.id and 
                machine_schedules.status = 1
                ) - ?) - machines.warning_period)', [
                $currentDate->toDateString(), $currentDate->toDateString()
            ])->whereRaw('(? - 
                (select MAX(maintenance.review_at)
                from maintenance
                where maintenance.machine_id = machines.id)) 
                < ((select MIN(machine_schedules.date::TIMESTAMP::DATE)
                from machine_schedules
                where machine_schedules.machine_id = machines.id and 
                machine_schedules.status = 1) - ?)', [
                $currentDate->toDateString(), $currentDate->toDateString()
            ]);
        })->get();
    }
}
