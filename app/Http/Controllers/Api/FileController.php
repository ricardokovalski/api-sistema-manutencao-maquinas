<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FileRepositoryContract;
use App\Services\Contracts\AuditServiceContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class FileController
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
        $this->middleware('auth:api');

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

            $uploadService->storeFile('machines/');

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param $id
     * @return JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show($id)
    {
        try {

            $file = $this->fileRepository->findFile($id);

            $headers = ['Content-Type: application/pdf'];

            $path = storage_path("app/public/machines/{$file->name}");

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

            $this->fileService->deleteFile($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

}