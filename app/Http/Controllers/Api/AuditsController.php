<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditsResponse;
use App\Repositories\Contracts\AuditRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuditsController extends Controller
{
    /**
     * @var AuditRepositoryContract
     */
    protected $auditRepository;

    /**
     * AuditsController constructor.
     * @param AuditRepositoryContract $auditRepository
     */
    public function __construct(AuditRepositoryContract $auditRepository)
    {
        $this->middleware('auth:api');

        $this->auditRepository = $auditRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $audits = $this->auditRepository->all();

            return (new AuditsResponse($audits))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
