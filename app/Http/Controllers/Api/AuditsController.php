<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditsResponse;
use App\Repositories\Audits\Criteria\FilterByAuditableTypeCriteria;
use App\Repositories\Audits\Criteria\FilterByEventCriteria;
use App\Repositories\Audits\Criteria\FilterByMachineCriteria;
use App\Repositories\Contracts\AuditRepositoryContract;
use App\Services\Contracts\MachineServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        //$this->middleware('auth:api');

        $this->auditRepository = $auditRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $options = get_class_methods(MachineServiceContract::class);
            array_push($options, \App\Entities\Machine::class);

            $audits = $this->auditRepository
                ->pushCriteria(new FilterByAuditableTypeCriteria($options))
                ->pushCriteria(new FilterByEventCriteria($request->get('action_id')))
                ->pushCriteria(new FilterByMachineCriteria($request->get('machine_id')))
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get(['*']);

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
