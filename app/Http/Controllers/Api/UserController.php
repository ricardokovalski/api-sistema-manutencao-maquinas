<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Http\Resources\UserResponse;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    /**
     * @var UserRepositoryContract
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        try {

            $users = $this->userRepository->all([
                'id', 'name', 'email',
            ]);

            return (new UserResponse($users))
                ->response()
                ->setStatusCode(200);

        } catch (\Exception $exception) {
            return (new UserResponse([]))
                ->response()
                ->setStatusCode(400);
        }
    }
}