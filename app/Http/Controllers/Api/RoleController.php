<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResponse;
use App\Repositories\Contracts\RoleRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryContract
     */
    protected $roleRepository;

    /**
     * RoleController constructor.
     * @param RoleRepositoryContract $roleRepository
     */
    public function __construct(RoleRepositoryContract $roleRepository)
    {
        $this->middleware('auth:api');

        $this->roleRepository = $roleRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $roles = $this->roleRepository->all([
                'id',
                'name',
            ]);

            return (new RoleResponse($roles))
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

            $role = $this->roleRepository->create($request->all());

            return (new RoleResponse($role))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {

            $role = $this->roleRepository->findById($id, [
                'id', 'name'
            ]);

            return (new RoleResponse($role))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
