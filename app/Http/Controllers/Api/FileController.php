<?php

namespace App\Http\Controllers\Api;

use App\Entities\Machine;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FileRepositoryContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 */
class FileController extends Controller
{

    /**
     * @var FileServiceContract
     */
    protected $fileService;

    /**
     * @var FileRepositoryContract
     */
    protected $fileRepository;

    /**
     * FileController constructor.
     * @param FileRepositoryContract $fileRepository
     * @param FileServiceContract $fileService
     */
    public function __construct(
        FileRepositoryContract $fileRepository,
        FileServiceContract $fileService
    ) {
        $this->fileRepository = $fileRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $uploadService = new UploadService($request->file('archive'));
            $uploadService->validate();

            $request->request->add([
                'title' => $uploadService->getOriginalName(),
                'name' => $uploadService->getNameEncrypt(),
                'type' => $uploadService->getOriginalExtension(),
            ]);

            $this->fileService->storeFile($request->except('archive'));

            $uploadService->storeFile('uploads/machines/');

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    public function show($id)
    {
        try {

            $file = $this->fileRepository->findFile($id);

            $headers = ['Content-Type: application/pdf'];
            $path = storage_path("app/uploads/machines/{$file->name}");

            //dd(file_get_contents(storage_path('app/'.$path)));

            return response()->download($path, $file->name, $headers);

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

            $file = $this->fileRepository->findFile($id);

            /**
             * Remove o vínculo das máquinas com a peça a ser deletada
             */
            $machinesFromFile = $file->machines->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $file->machines()->sync($machinesFromFile, false);

            $this->fileRepository->delete($id);

            $path = storage_path("app/uploads/machines/{$file->name}");

            @unlink($path);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

}