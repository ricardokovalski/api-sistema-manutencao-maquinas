<?php

namespace App\Repositories\Maintenance\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class JoinMachinesCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class JoinMachinesCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->join('machines', 'machines.id', '=', 'maintenance.machine_id');
    }
}
