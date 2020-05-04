<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MaintenanceRepositoryContract;
use App\Http\Resources\MaintenanceResponse;
use App\Repositories\Maintenance\Criteria\FilterByReviewAtCriteria;
use App\Repositories\Maintenance\Criteria\FilterByReviewTypeCriteria;
use App\Repositories\Maintenance\Criteria\FilterByTechnicalManagerCriteria;
use App\Repositories\Maintenance\Criteria\JoinMachinesCriteria;
use App\Repositories\Maintenance\Criteria\OrderByMachinesCriteria;
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
        $this->middleware('auth:api');

        $this->maintenanceRepository = $maintenanceRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $maintenance = $this->maintenanceRepository
                ->pushCriteria(new FilterByReviewAtCriteria($request->get('start_date'), $request->get('end_date')))
                ->pushCriteria(new FilterByReviewTypeCriteria($request->get('review_type_id')))
                ->pushCriteria(new FilterByTechnicalManagerCriteria($request->get('technical_manager_id')))
                ->pushCriteria(new JoinMachinesCriteria())
                ->pushCriteria(new OrderByMachinesCriteria())
                ->with([
                    'machine' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'reviewType' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'pieces' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])
                ->all([
                    'maintenance.id',
                    'maintenance.machine_id',
                    'maintenance.review_type_id',
                    'maintenance.description',
                    'maintenance.review_at',
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

            $data = $request->only(
                'description',
                'review_type_id',
                'machine_id',
                'review_at'
            );

            $maintenance = $this->maintenanceRepository->create($data);

            $maintenance->pieces()->attach($request->get('piece_id'), [
                'amount_used' => $request->get('amount_used'),
            ]);

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
                'pieces' => function ($query) {
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

            $maintenance->pieces()->detach();

            $this->maintenanceRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
