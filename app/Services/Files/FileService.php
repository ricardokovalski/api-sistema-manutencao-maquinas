<?php

namespace App\Services\Files;

use App\Exceptions\MachineException;
use App\Repositories\Contracts\FileRepositoryContract;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Services\Contracts\AuditServiceContract;
use App\Services\Contracts\FileServiceContract;
use Carbon\Carbon;
use Illuminate\Http\Response;

/**
 * Class MachineService
 * @package App\Services\Machines
 */
class FileService implements FileServiceContract
{
    /**
     * @var FileRepositoryContract
     */
    private $fileRepository;

    /**
     * @var MachineRepositoryContract
     */
    private $machineRepository;

    /**
     * MachineService constructor.
     * @param FileRepositoryContract $fileRepository
     * @param MachineRepositoryContract $machineRepository
     */
    public function __construct(
        FileRepositoryContract $fileRepository,
        MachineRepositoryContract $machineRepository
    ) {
        $this->fileRepository = $fileRepository;
        $this->machineRepository = $machineRepository;
    }

    /**[
     * @param array $request
     * @return bool
     * @throws MachineException
     */
    public function storeFile(array $request)
    {
        $machine = $this->machineRepository->findMachine($request['machine_id']);

        if (! $machine) {
            throw new MachineException('Máquina não encontrada.', Response::HTTP_NOT_FOUND);
        }

        $file = $this->fileRepository->create($request);

        $file->machines()->attach($machine, [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        app(AuditServiceContract::class)->create([
            'event' => 'created',
            'auditable_type' => 'assignFileFromMachine',
            'auditable_id' => $machine->id,
            'old_values' => [],
            'new_values' => [
                'machine_id' => $machine->id,
                'file_id' => $file->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteFile($id)
    {
        $file = $this->fileRepository->findFile($id);

        $pivot = $file->machines()->where('file_id', $file->id)->first()->pivot;

        app(AuditServiceContract::class)->create([
            'event' => 'deleted',
            'auditable_type' => 'removeFileFromMachine',
            'auditable_id' => $pivot->machine_id,
            'old_values' => [
                'machine_id' => $pivot->machine_id,
                'file_id' => $pivot->file_id,
                'created_at' => $pivot->created_at->toDateTimeString(),
                'updated_at' => $pivot->updated_at->toDateTimeString(),
            ],
            'new_values' => [
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);

        /**
         * Remove o vínculo das máquinas com a peça a ser deletada
         */
        $machinesFromFile = $file->machines->keyBy('id')->map(function() {
            return [
                'deleted_at' => Carbon::now()
            ];
        })->toArray();

        $file->machines()->sync($machinesFromFile, false);

        $this->fileRepository->delete($id);

        $path = storage_path("app/public/machines/{$file->name}");

        if (! @unlink($path)) {
            throw new \Exception("Ocorreu um erro ao remover o arquivo {$path}.", Response::HTTP_NOT_FOUND);
        }

        return true;
    }
}
