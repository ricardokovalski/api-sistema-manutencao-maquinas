<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Http\Resources\MachineResponse;
use App\Repositories\Contracts\UserRepositoryContract;
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
     * @var UserRepositoryContract
     */
    protected $userRepository;

    /**
     * MachineController constructor.
     * @param MachineRepositoryContract $machineRepository
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(
        MachineRepositoryContract $machineRepository,
        UserRepositoryContract $userRepository
    ) {
        $this->machineRepository = $machineRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $machines = $this->machineRepository
                ->with([
                    'users' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                ])
                ->all([
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

            $machine = $this->machineRepository
                ->with([
                    'users' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                ])
                ->find($id, [
                    'id',
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

            $machine = $this->machineRepository->find($id);

            if ($machine->maintenance->pluck('id')) {
                throw new \Exception('Não é possível remover está máquina, pois existem manutenções vinculadas à está máquina.', Response::HTTP_NOT_FOUND);
            }

            $machine->users()->detach();

            $this->machineRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

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
    public function assignUser(Request $request): JsonResponse
    {
        try {

            $machine = $this->machineRepository->find($request->get('machine_id'));

            if (! $machine) {
                throw new \Exception('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
            }

            $user = $this->userRepository->find($request->get('user_id'));

            if (! $user) {
                throw new \Exception('Usuário não encontrado.', Response::HTTP_NOT_FOUND);
            }

            $machine->users()->attach([$user]);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
