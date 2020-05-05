<?php

namespace App\Services\Maintenance;

use App\Entities\Maintenance;
use App\Exceptions\PieceException;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Services\Contracts\AuditServiceContract;
use App\Services\Contracts\MaintenanceServiceContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class MaintenanceService
 * @package App\Services\Machines
 */
class MaintenanceService implements MaintenanceServiceContract
{
    /**
     * @var PeaceRepositoryContract
     */
    private $pieceRepository;

    /**
     * MaintenanceService constructor.
     * @param PeaceRepositoryContract $pieceRepository
     */
    public function __construct(
         PeaceRepositoryContract $pieceRepository
    ) {
        $this->pieceRepository = $pieceRepository;
    }

    /**
     * @param Maintenance $maintenance
     * @param array $request
     * @return bool
     * @throws PieceException
     */
    public function assignPieceFromMaintenance(Maintenance $maintenance, array $request)
    {
        $piece = $this->pieceRepository->findPiece($request['piece_id']);

        if (! $piece) {
            throw new PieceException('Peça não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $maintenance->pieces()->attach($piece->id, [
            'amount_used' => $request['amount_used'],
        ]);

        app(AuditServiceContract::class)->create([
            'event' => 'created',
            'auditable_type' => 'assignPieceFromMaintenance',
            'auditable_id' => $maintenance->id,
            'old_values' => [],
            'new_values' => [
                'maintenance_id' => $maintenance->id,
                'peace_id' => $piece->id,
                'amount_used' => $request['amount_used'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        return true;
    }
}