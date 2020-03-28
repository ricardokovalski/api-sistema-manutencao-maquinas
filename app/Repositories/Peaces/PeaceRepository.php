<?php

namespace App\Repositories\Peaces;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Entities\Peace;

class PeaceRepository extends BaseRepository implements PeaceRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Peace::class;
    }
    
}
