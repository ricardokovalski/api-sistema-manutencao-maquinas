<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewTypeResponse;
use App\Repositories\Contracts\ReviewTypeRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReviewTypeController extends Controller
{

    /**
     * @var ReviewTypeRepositoryContract
     */
    protected $reviewTypeRepository;

    /**
     * ReviewTypeController constructor.
     * @param ReviewTypeRepositoryContract $reviewTypeRepository
     */
    public function __construct(ReviewTypeRepositoryContract $reviewTypeRepository)
    {
        $this->reviewTypeRepository = $reviewTypeRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $reviewTypes = $this->reviewTypeRepository->all([
                'id',
                'name',
            ]);

            return (new ReviewTypeResponse($reviewTypes))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
