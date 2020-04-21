<?php

namespace App\Repositories\Machines;

use App\Entities\Machine;
use App\Repositories\Contracts\MachineRepositoryContract;
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

    public function findMachine($id)
    {
        return $this->findByField('id', $id)->first();
    }
    
}
