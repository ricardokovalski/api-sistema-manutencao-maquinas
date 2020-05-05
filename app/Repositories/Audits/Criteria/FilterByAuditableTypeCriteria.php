<?php

namespace App\Repositories\Audits\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByAuditableTypeCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class FilterByAuditableTypeCriteria implements CriteriaInterface
{
    protected $autitableType = [];

    /**
     * FilterByAuditableTypeCriteria constructor.
     * @param $auditableType
     */
    public function __construct(array $auditableType = null)
    {
        $this->autitableType = $auditableType;
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
        if (! $this->autitableType) {
            return $model;
        }

        return $model->whereIn('auditable_type', $this->autitableType);
    }
}
