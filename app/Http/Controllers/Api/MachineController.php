<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Http\Resources\MachineResponse;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\MachineServiceContract;
use App\Services\EmailService;
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
     * @var PeaceRepositoryContract
     */
    protected $peaceRepository;

    /**
     * @var EmailService
     */
    private $emailService;

    /**
     * @var MachineServiceContract
     */
    protected $machineService;

    /**
     * MachineController constructor.
     * @param MachineRepositoryContract $machineRepository
     * @param UserRepositoryContract $userRepository
     * @param PeaceRepositoryContract $peaceRepository
     * @param MachineServiceContract $machineService
     * @param EmailService $emailService
     */
    public function __construct(
        MachineRepositoryContract $machineRepository,
        UserRepositoryContract $userRepository,
        PeaceRepositoryContract $peaceRepository,
        MachineServiceContract $machineService,
        EmailService $emailService
    ) {
        $this->machineRepository = $machineRepository;
        $this->userRepository = $userRepository;
        $this->peaceRepository = $peaceRepository;
        $this->machineService = $machineService;
        $this->emailService = $emailService;
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
                        $query->select(
                            'users.id',
                            'users.name',
                            'users.email',
                            'users.telephone',
                            'users.additional'
                        );
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                    'pieces' => function ($query) {
                        $query->select(
                            'peaces.id',
                            'peaces.code',
                            'peaces.name',
                            'peaces.description',
                            'peaces.stock_quantity',
                            'peaces.minimal_quantity'
                        );
                    },
                ])
                ->orderBy('machines.name', 'asc')
                ->all([
                    'machines.id',
                    'machines.name',
                    'machines.description',
                    'machines.technical',
                    'machines.patrimony',
                    'machines.review_period',
                    'machines.warning_period',
                    'machines.warning_email_address',
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
                        $query->select(
                            'users.id',
                            'users.name',
                            'users.email',
                            'users.telephone',
                            'users.additional'
                        );
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                    'pieces' => function ($query) {
                        $query->select(
                            'peaces.id',
                            'peaces.code',
                            'peaces.name',
                            'peaces.description',
                            'peaces.stock_quantity',
                            'peaces.minimal_quantity'
                        );
                    },
                ])
                ->find($id, [
                    'machines.id',
                    'machines.name',
                    'machines.description',
                    'machines.technical',
                    'machines.patrimony',
                    'machines.review_period',
                    'machines.warning_period',
                    'machines.warning_email_address',
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

            $this->machineService->assignUser($request);

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
    public function assignPiece(Request $request): JsonResponse
    {
        try {

            $this->machineService->assignPiece($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    public function sendEmail()
    {
        try {

            $job = new SendEmail([
                'nameTo' => 'Teste Teste',
                'emailTo' => 'teste@gmail.com'
            ]);
            $this->dispatch($job);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
