<?php

namespace App\Services\Machines;

use App\Exceptions\MachineException;
use App\Exceptions\PieceException;
use App\Exceptions\UserException;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\MachineServiceContract;
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

        $machine->users()->sync([
            $user->id => ['deleted_at' => \Carbon\Carbon::now()]
        ]);

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

        $machine->pieces()->sync([
            $piece->id => ['deleted_at' => \Carbon\Carbon::now()]
        ]);

        return true;
    }
}
