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
     * @param $startDate
     * @param $endDate
     * @param string $column
     */
    public function __construct($startDate, $endDate, $column = 'review_at')
    {
        $this->startDate = $startDate . ' 00:00:00';
        $this->endDate = $endDate . ' 00:00:00';
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
            $model = $model->where($this->column, '>=', $this->startDate);
        }

        if ($this->endDate) {
            $model = $model->where($this->column, '<=', $this->endDate);
        }

        return $model;
    }
}
