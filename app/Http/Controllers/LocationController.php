<?php


namespace App\Http\Controllers;


use App\Services\Location\LocationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private LocationServiceInterface $locationService;

    public function __construct(LocationServiceInterface $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function searchCity(Request $request): JsonResponse
    {
        $this->validate(
            $request,
            [
                'search' => 'string',
                'page' => 'required|integer',
                'per_page' => 'required|integer'
            ]
        );

        $search = $request->input('search');
        $page = $request->input('page');
        $perPage = $request->input('per_page');

        $result = $this->locationService->searchCity($search, $page, $perPage);
        return response()->json($result, 200);
    }

}
