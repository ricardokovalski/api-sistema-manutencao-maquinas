<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Http\Resources\PeaceResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PeaceController
 * @package App\Http\Controllers\Api
 */
class PeaceController extends Controller
{
    /**
     * @var PeaceRepositoryContract
     */
    protected $peaceRepository;

    /**
     * PeaceController constructor.
     * @param PeaceRepositoryContract $peaceRepository
     */
    public function __construct(PeaceRepositoryContract $peaceRepository)
    {
        $this->middleware('auth:api');

        $this->peaceRepository = $peaceRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $peaces = $this->peaceRepository
                ->with([
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id');
                    },
                ])
                ->orderBy('code', 'asc')
                ->all([
                    'id',
                    'code',
                    'name',
                    'description',
                    'stock_quantity',
                    'minimal_quantity',
                ]);

            return (new PeaceResponse($peaces))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $peace = $this->peaceRepository->create($request->all());

            return (new PeaceResponse($peace))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

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
    public function show($id): JsonResponse
    {
        try {

            $peace = $this->peaceRepository
                ->with([
                    'maintenance' => function ($query) {
                        $query->select('id', 'machine_id', 'review_type_id');
                    },
                ])
                ->find($id, [
                    'id',
                    'code',
                    'name',
                    'description',
                    'stock_quantity',
                    'minimal_quantity',
                ]);

            return (new PeaceResponse($peace))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {

            $peace = $this->peaceRepository->update(
                $request->all(),
                $id
            );

            return (new PeaceResponse($peace))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

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

            $peace = $this->peaceRepository->find($id);

            $peace->maintenance()->detach();

            /**
             * Remove o vínculo das máquinas com a peça a ser deletada
             */
            $machinesFromPiece = $peace->machines->keyBy('id')->map(function() {
                return [
                    'deleted_at' => \Carbon\Carbon::now()
                ];
            })->toArray();

            $peace->machines()->sync($machinesFromPiece, false);

            $this->peaceRepository->delete($id);

            return response()->json(true, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        }
    }
}
