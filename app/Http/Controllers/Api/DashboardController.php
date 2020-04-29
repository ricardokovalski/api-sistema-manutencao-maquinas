<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\DashboardServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 */
class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardServiceContract $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            return response()->json([
                'red' => $this->dashboardService->getRed(),
                'yellow' => $this->dashboardService->getYellow(),
                'green' => $this->dashboardService->getGreen(),
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}