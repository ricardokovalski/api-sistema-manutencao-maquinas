<?php

namespace App\Repositories\Audits\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByMachineCriteria
 * @package App\Repositories\Audits\Criteria
 */
class FilterByMachineCriteria implements CriteriaInterface
{
    /**
     * @var null
     */
    protected $machineId;

    /**
     * FilterByEventCriteria constructor.
     * @param null $machineId
     */
    public function __construct($machineId = null)
    {
        $this->machineId = $machineId;
    }

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
        if (! $this->machineId) {
            return $model;
        }

        return $model->where('auditable_id', $this->machineId);
    }
}
