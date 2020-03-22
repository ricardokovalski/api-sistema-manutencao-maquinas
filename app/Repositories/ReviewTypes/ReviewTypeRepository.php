<?php

namespace App\Repositories\ReviewTypes;

use App\Entities\ReviewType;
use App\Repositories\Contracts\ReviewTypeRepositoryContract;
use Prettus\Repository\Eloquent\BaseRepository;

class ReviewTypeRepository extends BaseRepository implements ReviewTypeRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ReviewType::class;
    }
}
