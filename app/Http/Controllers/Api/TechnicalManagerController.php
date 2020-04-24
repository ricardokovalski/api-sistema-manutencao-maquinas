<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Http\Resources\UserResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TechnicalManagerController
 * @package App\Http\Controllers\Api
 */
class TechnicalManagerController extends Controller
{
    /**
     * @var UserRepositoryContract
     */
    protected $userRepository;

    /**
     * @var RoleRepositoryContract
     */
    protected $roleRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryContract $userRepository
     * @param RoleRepositoryContract $roleRepository
     */
    public function __construct(
        UserRepositoryContract $userRepository,
        RoleRepositoryContract $roleRepository
    ) {
        /*$this->middleware('auth:api', [
            'except' => ['store']
        ]);*/

        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $users = $this->userRepository
                ->getTechnicalManagers([
                    'id',
                    'name',
                    'email',
                    'telephone',
                    'additional',
                ]);

            return (new UserResponse($users))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $user = $this->userRepository->create($request->all());

            $role = $this->roleRepository->findById($request->get('profile_id'));

            if (! $role) {
                throw new \Exception('Perfil não encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user->assignRole($request->get('profile_id'));

            return (new UserResponse($user))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {

            $user = $this->userRepository
                ->with(['machines' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->find($id, [
                    'id',
                    'name',
                    'email',
                    'telephone',
                    'additional',
                ]);

            return (new UserResponse($user))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {

            $user = $this->userRepository->update(
                $request->all(),
                $id
            );

            $role = $this->roleRepository->findById($request->get('profile_id'));

            if (! $role) {
                throw new \Exception('Perfil não encontrado!', Response::HTTP_NOT_FOUND);
            }

            $user->assignRole($request->get('profile_id'));

            return (new UserResponse($user))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {

            $technical = $this->userRepository->find($id);

            $machinesFromTechnical = $technical->machines->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $technical->machines()->sync($machinesFromTechnical, false);

            $this->userRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}