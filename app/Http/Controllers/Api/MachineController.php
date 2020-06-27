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
        $this->middleware('auth:api');

        $this->machineRepository = $machineRepository;
        $this->userRepository = $userRepository;
        $this->peaceRepository = $peaceRepository;
        $this->machineService = $machineService;
        $this->emailService = $emailService;
    }

    /**
     * @OA\Get(
     *     tags={"Machines"},
     *     path="/api/machines",
     *     summary="List of machines",
     *     description="Return a list of machines",
     *     @OA\Response(response="200", description="An json"),
     *      security={
     *           {"apiKey": {}}
     *       }
     * )
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
                    'files' => function ($query) {
                        $query->select(
                            'files.id',
                            'files.name',
                            'files.title',
                            'files.description',
                            'files.type'
                        );
                    },
                    'schedules' => function ($query) {
                        $query->select('machines_schedules.date');
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
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines",
     *      summary="Store a machine",
     *      description="Return a machine",
     *      @OA\Parameter(
     *          name="name",
     *          description="Name field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="technical",
     *          description="Technical",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="patrimony",
     *          description="Patrimony",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Store machine"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $machine = $this->machineService->storeMachine($request);

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
     * @OA\Get(
     *     tags={"Machines"},
     *     path="/api/machines/{id}",
     *     operationId="getMachineById",
     *     @OA\Parameter(
     *          name ="id",
     *          in = "path",
     *          description = "ID of machine to return",
     *          required = true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     summary="Show a machine",
     *     description="Return a machine",
     *     @OA\Response(response="200", description="An json"),
     *     security={
     *           {"apiKey": {}}
     *     }
     * )
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
                    'files' => function ($query) {
                        $query->select(
                            'files.id',
                            'files.name',
                            'files.title',
                            'files.description',
                            'files.type'
                        );
                    },
                    'schedules' => function ($query) {
                        $query->select('machines_schedules.date');
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
     * @OA\Put(
     *      tags={"Machines"},
     *      path="/api/machines/{id}",
     *      summary="Update a machine",
     *      description="Update a machine",
     *      operationId="getMachineById",
     *      @OA\Parameter(
     *          name ="id",
     *          in = "path",
     *          description = "ID of machine to return",
     *          required = true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="Name field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="technical",
     *          description="Technical",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="patrimony",
     *          description="Patrimony",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Update machine"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $machine = $this->machineService->updateMachine($request, $id);

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
     * @OA\Delete(
     *     tags={"Machines"},
     *     path="/api/machines/{id}",
     *     operationId="getMachineById",
     *     @OA\Parameter(
     *          name ="id",
     *          in = "path",
     *          description = "ID of machine to return",
     *          required = true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     summary="Delete a machine",
     *     description="Delete a machine",
     *     @OA\Response(response="200", description="An json"),
     *     security={
     *           {"apiKey": {}}
     *     }
     * )
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {

            $machine = $this->machineRepository->find($id);

            if ($machine->maintenance->pluck('id')->first()) {
                throw new \Exception('Não é possível remover está máquina, pois existem manutenções vinculadas à está máquina.', Response::HTTP_NOT_FOUND);
            }

            /**
             * Remove o vínculo dos usuários com a máquina a ser deletada
             */
            $usersFromMachine = $machine->users->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $machine->users()->sync($usersFromMachine, false);

            /**
             * Remove o vínculo das peças com a máquina a ser deletada
             */
            $piecesFromMachine = $machine->pieces->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $machine->pieces()->sync($piecesFromMachine, false);

            /**
             * Remove o vínculo dos arquivos com a máquina a ser deletada
             */
            $filesFromMachine = $machine->files->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $machine->files()->sync($filesFromMachine, false);

            /**
             * Deleta a máquina
             */
            $this->machineRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines/technical-manager",
     *      summary="Assign a Technical-manager in a machine",
     *      description="Return a boolean",
     *      @OA\Parameter(
     *          name="machine_id",
     *          description="ID of machine",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          description="ID of technical-manager",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="An boolean"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function assignUser(Request $request): JsonResponse
    {
        try {

            $this->machineService->assignTechnicalManagerFromMachine($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * /**
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines/technical-manager/remove",
     *      summary="Remove a Technical-manager from machine",
     *      description="Return a boolean",
     *      @OA\Parameter(
     *          name="machine_id",
     *          description="ID of machine",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          description="ID of technical-manager",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="An boolean"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function removeUser(Request $request): JsonResponse
    {
        try {

            $this->machineService->removeTechnicalManagerFromMachine($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines/piece",
     *      summary="Assign a Piece in a machine",
     *      description="Return a boolean",
     *      @OA\Parameter(
     *          name="machine_id",
     *          description="ID of machine",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="piece_id",
     *          description="ID of piece",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="minimal_quantity",
     *          description="minimal quantity of pieces",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="An boolean"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function assignPiece(Request $request): JsonResponse
    {
        try {

            $this->machineService->assignPieceFromMachine($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines/piece/remove",
     *      summary="Remove a Piece from machine",
     *      description="Return a boolean",
     *      @OA\Parameter(
     *          name="machine_id",
     *          description="ID of machine",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="piece_id",
     *          description="ID of piece",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="An boolean"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function removePiece(Request $request): JsonResponse
    {
        try {

            $this->machineService->removePieceFromMachine($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @OA\Post(
     *      tags={"Machines"},
     *      path="/api/machines/schedule/remove",
     *      summary="Remove schedules from machine",
     *      description="Return a boolean",
     *      @OA\Parameter(
     *          name="machine_id",
     *          description="ID of machine",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="An boolean"),
     *      security={
     *           {"apiKey": {}}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function removeSchedule(Request $request): JsonResponse
    {
        try {

            $this->machineService->removeScheduleFromMachine($request);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
