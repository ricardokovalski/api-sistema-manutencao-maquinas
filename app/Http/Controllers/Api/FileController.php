<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\FileServiceContract;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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
     * FileController constructor.
     * @param FileServiceContract $fileService
     */
    public function __construct(FileServiceContract $fileService)
    {
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

            list($file, $path) = $this->fileService->findFile($id);

            $headers = ['Content-Type: application/pdf'];

            //dd(file_get_contents(storage_path('app/'.$path)));

            return response()->download(storage_path('app/'.$path), $file->name, $headers);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }



}