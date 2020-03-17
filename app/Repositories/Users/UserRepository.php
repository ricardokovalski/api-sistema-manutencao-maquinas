<?php

namespace App\Repositories\Users;

use App\Entities\User;
use Prettus\Repository\Eloquent\BaseRepository;
//use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\UserRepositoryContract;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /*public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }*/
}
