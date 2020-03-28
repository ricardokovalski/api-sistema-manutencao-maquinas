<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Http\Resources\MachineResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MachineController
 * @package App\Http\Controllers\Api
 */
class MachineController extends Controller
{
    /**
     * @var MachineRepositoryContract
     */
    protected $machineRepository;

    /**
     * MachineController constructor.
     * @param MachineRepositoryContract $machineRepository
     */
    public function __construct(MachineRepositoryContract $machineRepository)
    {
        /*$this->middleware('auth:api', [
            'except' => ['store']
        ]);*/

        $this->machineRepository = $machineRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $machines = $this->machineRepository->all([
                'id',
                'name',
                'description',
                'technical',
                'patrimony',
                'review_period',
                'warning_period',
                'warning_email_address',
            ]);

            return (new MachineResponse($machines))
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

            $machine = $this->machineRepository->create($request->all());

            return (new MachineResponse($machine))
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

            $machine = $this->machineRepository->find($id, [
                'name',
                'description',
                'technical',
                'patrimony',
                'review_period',
                'warning_period',
                'warning_email_address',
            ]);

            return (new MachineResponse($machine))
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

            $machine = $this->machineRepository->update(
                $request->all(),
                $id
            );

            return (new MachineResponse($machine))
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

            return response()->json([
                'data' => $this->machineRepository->delete($id)
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
