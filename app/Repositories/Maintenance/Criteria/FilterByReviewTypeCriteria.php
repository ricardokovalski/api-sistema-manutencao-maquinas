<?php

namespace App\Repositories\Maintenance\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByReviewTypeCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class FilterByReviewTypeCriteria implements CriteriaInterface
{
    protected $reviewTypeId;

    /**
     * FilterByReviewTypeCriteria constructor.
     * @param $reviewTypeId
     */
    public function __construct($reviewTypeId)
    {
        $this->reviewTypeId = $reviewTypeId;
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
        if (! $this->reviewTypeId) {
            return $model;
        }

        return $model->where('review_type_id', $this->reviewTypeId);
    }
}
