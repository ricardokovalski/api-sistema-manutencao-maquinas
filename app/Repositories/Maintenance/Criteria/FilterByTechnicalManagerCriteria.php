<?php

namespace App\Repositories\Maintenance\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByTechnicalManagerCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class FilterByTechnicalManagerCriteria implements CriteriaInterface
{
    protected $technicalManagerId;

    /**
     * FilterByTechnicalManagerCriteria constructor.
     * @param $technicalManagerId
     */
    public function __construct($technicalManagerId)
    {
        $this->technicalManagerId = $technicalManagerId;
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
        if (! $this->technicalManagerId) {
            return $model;
        }

        return $model->whereHas('machine.users', function ($query) {
            return $query->where('user_id', $this->technicalManagerId);
        });
    }
}
