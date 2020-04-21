<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 */
class DashboardController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            return response()->json([
                'red' => rand(0, 10),
                'yellow' => rand(0, 15),
                'green' => rand(0, 5),
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}