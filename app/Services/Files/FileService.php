<?php

namespace App\Services\Files;

use App\Exceptions\MachineException;
use App\Repositories\Contracts\FileRepositoryContract;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Http\Request;
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
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        return true;
    }

    public function findFile($id)
    {
        $file = $this->fileRepository->findFile($id);
        $path =  "uploads/machines/{$file->name}";

        return array($file, $path);
    }

}
