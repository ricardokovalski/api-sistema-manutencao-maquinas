<?php

namespace App\Repositories\Users;

use App\Entities\User;
use App\Repositories\Contracts\UserRepositoryContract;
//use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

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
