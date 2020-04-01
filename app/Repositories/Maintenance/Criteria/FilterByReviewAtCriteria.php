<?php

namespace App\Repositories\Maintenance\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByReviewAtCriteria
 * @package App\Repositories\Maintenance\Criteria
 */
class FilterByReviewAtCriteria implements CriteriaInterface
{
    protected $startDate;

    protected $endDate;

    protected $column;

    /**
     * FilterByReviewAtCriteria constructor.
     * @param null $startDate
     * @param null $endDate
     * @param string $column
     */
    public function __construct($startDate = null, $endDate = null, $column = 'review_at')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->column = $column;
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
        if (! $this->startDate && ! $this->endDate) {
            return $model;
        }

        if ($this->startDate) {
            $model = $model->where($this->column, '>=', $this->startDate . ' 00:00:00');
        }

        if ($this->endDate) {
            $model = $model->where($this->column, '<=', $this->endDate . ' 00:00:00');
        }

        return $model;
    }
}
