<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index()
    {
        try {
            return response()->json(
                \App\Entities\User::all(),
                200
            );
        } catch (\Exception $exception) {
            return response()->json(
                [],
                400
            );
        }
    }
}