<?php

namespace App\Repositories\Audits\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByEventCriteria
 * @package App\Repositories\Audits\Criteria
 */
class FilterByEventCriteria implements CriteriaInterface
{
    /**
     * @var null
     */
    protected $event;

    /**
     * FilterByEventCriteria constructor.
     * @param null $event
     */
    public function __construct($event = null)
    {
        $this->event = $event;
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
        if (! $this->event) {
            return $model;
        }

        return $model->where('event', $this->event);
    }
}
