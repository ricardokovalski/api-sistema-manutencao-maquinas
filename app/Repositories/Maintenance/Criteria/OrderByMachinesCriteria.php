<?php

namespace App\Repositories\Maintenance\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrderByMachinesCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class OrderByMachinesCriteria implements CriteriaInterface
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
        return $model->orderBy('machines.name', 'asc');
    }
}
