<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MaintenanceRepositoryContract;
use App\Http\Resources\MaintenanceResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MaintenanceController
 * @package App\Http\Controllers\Api
 */
class MaintenanceController extends Controller
{
    protected $maintenanceRepository;

    public function __construct(MaintenanceRepositoryContract $maintenanceRepository)
    {
        $this->maintenanceRepository = $maintenanceRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $maintenance = $this->maintenanceRepository
                ->with([
                    'machine' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'reviewType' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'peaces' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])
                ->all([
                    'id',
                    'machine_id',
                    'review_type_id',
                    'description',
                    'review_at',
                ]);

            return (new MaintenanceResponse($maintenance))
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

            $maintenance = $this->maintenanceRepository->create($request->all());

            return (new MaintenanceResponse($maintenance))
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
    public function show($id): JsonResponse
    {
        try {

            $maintenance = $this->maintenanceRepository->with([
                'machine' => function ($query) {
                    $query->select('id', 'name');
                },
                'reviewType' => function ($query) {
                    $query->select('id', 'name');
                },
                'peaces' => function ($query) {
                    $query->select('id', 'name');
                },
            ])->find($id, [
                'id',
                'machine_id',
                'review_type_id',
                'description',
                'review_at',
            ]);

            return (new MaintenanceResponse($maintenance))
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
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $maintenance = $this->maintenanceRepository->update(
                $request->all(),
                $id
            );

            return (new MaintenanceResponse($maintenance))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

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
    public function destroy($id): JsonResponse
    {
        try {

            $maintenance = $this->maintenanceRepository->find($id);

            $maintenance->peaces()->detach();

            $this->maintenanceRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
