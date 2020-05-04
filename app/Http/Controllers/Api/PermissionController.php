<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResponse;
use App\Repositories\Contracts\PermissionRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepositoryContract
     */
    protected $permissionRepository;

    /**
     * PermissionController constructor.
     * @param PermissionRepositoryContract $permissionRepository
     */
    public function __construct(PermissionRepositoryContract $permissionRepository)
    {
        $this->middleware('auth:api');

        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $roles = $this->permissionRepository->all([
                'id',
                'name',
            ]);

            return (new PermissionResponse($roles))
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

            $role = $this->permissionRepository->create($request->all());

            return (new PermissionResponse($role))
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

            $role = $this->permissionRepository->findById($id, [
                'id', 'name'
            ]);

            return (new PermissionResponse($role))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
