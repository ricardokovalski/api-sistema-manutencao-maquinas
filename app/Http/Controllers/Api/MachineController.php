<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Http\Resources\MachineResponse;
use App\Repositories\Contracts\PeaceRepositoryContract;
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
     * @var PeaceRepositoryContract
     */
    protected $peaceRepository;

    /**
     * MachineController constructor.
     * @param MachineRepositoryContract $machineRepository
     * @param UserRepositoryContract $userRepository
     * @param PeaceRepositoryContract $peaceRepository
     */
    public function __construct(
        MachineRepositoryContract $machineRepository,
        UserRepositoryContract $userRepository,
        PeaceRepositoryContract $peaceRepository
    ) {
        $this->machineRepository = $machineRepository;
        $this->userRepository = $userRepository;
        $this->peaceRepository = $peaceRepository;
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
                        $query->select('id', 'name', 'email', 'telephone', 'additional');
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                    'pieces' => function ($query) {
                        $query->select('id', 'code', 'name', 'description', 'stock_quantity', 'minimal_quantity');
                    },
                ])
                ->orderBy('name', 'asc')
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
                        $query->select('id', 'name', 'email', 'telephone', 'additional');
                    },
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id', 'review_at');
                    },
                    'pieces' => function ($query) {
                        $query->select('id', 'code', 'name', 'description', 'stock_quantity', 'minimal_quantity');
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

            $machine = $this->machineRepository
                ->findByField('id', $request->get('machine_id'))
                ->first();

            if (! $machine) {
                throw new \Exception('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
            }

            $user = $this->userRepository
                ->findByField('id', $request->get('user_id'))
                ->first();

            if (! $user) {
                throw new \Exception('Usuário não encontrado.', Response::HTTP_NOT_FOUND);
            }

            $machineWithUser = $machine->whereHas('users', function ($query) use ($user, $machine) {
                return $query->where('machine_users.user_id', $user->id)
                    ->where('machine_users.machine_id', $machine->id);
            })->first();

            if ($machineWithUser) {
                throw new \Exception('Esta máquina já possui esse Responsável Técnico vinculado!', Response::HTTP_NOT_FOUND);
            }

            $machine->users()->attach($user);

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

            $machine = $this->machineRepository
                ->findByField('id', $request->get('machine_id'))
                ->first();

            if (! $machine) {
                throw new \Exception('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
            }

            $piece = $this->peaceRepository
                ->findByField('id', $request->get('piece_id'))
                ->first();

            if (! $piece) {
                throw new \Exception('Peça não encontrada.', Response::HTTP_NOT_FOUND);
            }

            $machineWithPiece = $machine->whereHas('pieces', function ($query) use ($piece, $machine) {
                return $query->where('machine_pieces.piece_id', $piece->id)
                    ->where('machine_pieces.machine_id', $machine->id);
            })->first();

            if ($machineWithPiece) {
                throw new \Exception('Esta máquina já possui essa peça de reposição!', Response::HTTP_NOT_FOUND);
            }

            $machine->pieces()->attach($piece, [
                'minimal_quantity' => $request->get('minimal_quantity'),
            ]);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
