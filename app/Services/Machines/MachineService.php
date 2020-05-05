<?php

namespace App\Services\Machines;

use App\Exceptions\MachineException;
use App\Exceptions\PieceException;
use App\Exceptions\UserException;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\AuditServiceContract;
use App\Services\Contracts\MachineServiceContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MachineService
 * @package App\Services\Machines
 */
class MachineService implements MachineServiceContract
{
    /**
     * @var MachineRepositoryContract
     */
    private $machineRepository;

    /**
     * @var UserRepositoryContract
     */
    private $userRepository;

    /**
     * @var PeaceRepositoryContract
     */
    private $peaceRepository;

    /**
     * MachineService constructor.
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
     * @param Request $request
     * @return bool
     * @throws MachineException
     * @throws UserException
     */
    public function assignTechnicalManagerFromMachine(Request $request)
    {
        $machine = $this->machineRepository
            ->findMachine($request->get('machine_id'));

        if (! $machine) {
            throw new MachineException('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository
            ->findUser($request->get('user_id'));

        if (! $user) {
            throw new UserException('Usuário não encontrado.', Response::HTTP_NOT_FOUND);
        }

        $machineWithUser = $machine->whereHas('users', function ($query) use ($user, $machine) {
            return $query->where('machine_users.user_id', $user->id)
                ->where('machine_users.machine_id', $machine->id);
        })->first();

        if ($machineWithUser) {
            throw new MachineException('Esta máquina já possui esse Responsável Técnico vinculado!', Response::HTTP_NOT_FOUND);
        }

        $machine->users()->attach($user);

        app(AuditServiceContract::class)->create([
            'event' => 'created',
            'auditable_type' => 'assignTechnicalManagerFromMachine',
            'auditable_id' => $machine->id,
            'old_values' => [],
            'new_values' => [
                'machine_id' => $machine->id,
                'user_id' => $user->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws MachineException
     * @throws UserException
     */
    public function removeTechnicalManagerFromMachine(Request $request)
    {
        $machine = $this->machineRepository
            ->findMachine($request->get('machine_id'));

        if (! $machine) {
            throw new MachineException('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository
            ->findUser($request->get('user_id'));

        if (! $user) {
            throw new UserException('Usuário não encontrado.', Response::HTTP_NOT_FOUND);
        }

        $pivot = $machine->users()->where('user_id', $user->id)->first()->pivot;

        app(AuditServiceContract::class)->create([
            'event' => 'deleted',
            'auditable_type' => 'removeTechnicalManagerFromMachine',
            'auditable_id' => $pivot->machine_id,
            'old_values' => [
                'machine_id' => $pivot->machine_id,
                'user_id' => $pivot->user_id,
                'created_at' => $pivot->created_at->toDateTimeString(),
                'updated_at' => $pivot->updated_at->toDateTimeString(),
            ],
            'new_values' => [
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        $machine->users()->sync([
            $user->id => ['deleted_at' => Carbon::now()]
        ], false);

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws MachineException
     * @throws PieceException
     */
    public function assignPieceFromMachine(Request $request)
    {
        $machine = $this->machineRepository
            ->findMachine($request->get('machine_id'));

        if (! $machine) {
            throw new MachineException('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $piece = $this->peaceRepository
            ->findPiece($request->get('piece_id'));

        if (! $piece) {
            throw new PieceException('Peça não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $machineWithPiece = $machine->whereHas('pieces', function ($query) use ($piece, $machine) {
            return $query->where('machine_pieces.piece_id', $piece->id)
                ->where('machine_pieces.machine_id', $machine->id);
        })->first();

        if ($machineWithPiece) {
            throw new MachineException('Esta máquina já possui essa peça de reposição!', Response::HTTP_NOT_FOUND);
        }

        $machine->pieces()->attach($piece, [
            'minimal_quantity' => $request->get('minimal_quantity'),
        ]);

        app(AuditServiceContract::class)->create([
            'event' => 'created',
            'auditable_type' => 'assignPieceFromMachine',
            'auditable_id' => $machine->id,
            'old_values' => [],
            'new_values' => [
                'machine_id' => $machine->id,
                'piece_id' => $piece->id,
                'minimal_quantity' => $request->get('minimal_quantity'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws MachineException
     * @throws PieceException
     */
    public function removePieceFromMachine(Request $request)
    {
        $machine = $this->machineRepository
            ->findMachine($request->get('machine_id'));

        if (! $machine) {
            throw new MachineException('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $piece = $this->peaceRepository
            ->findPiece($request->get('piece_id'));

        if (! $piece) {
            throw new PieceException('Peça não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $pivot = $machine->pieces()->where('piece_id', $piece->id)->first()->pivot;

        app(AuditServiceContract::class)->create([
            'event' => 'deleted',
            'auditable_type' => 'removePieceFromMachine',
            'auditable_id' => $pivot->machine_id,
            'old_values' => [
                'machine_id' => $pivot->machine_id,
                'piece_id' => $pivot->piece_id,
                'minimal_quantity' => $request->get('minimal_quantity'),
                'created_at' => $pivot->created_at->toDateTimeString(),
                'updated_at' => $pivot->updated_at->toDateTimeString(),
            ],
            'new_values' => [
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        $machine->pieces()->sync([
            $piece->id => ['deleted_at' => Carbon::now()]
        ], false);

        return true;
    }
}
