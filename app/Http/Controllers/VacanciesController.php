<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Vacancy\VacancyServiceInterface;

class VacanciesController extends Controller
{
    private VacancyServiceInterface $vacancyService;

    public function __construct( VacancyServiceInterface $vacancyService)
    {
        $this->vacancyService = $vacancyService;
    }

    public function getVacanciesList(Request $request): JsonResponse
    {
        $this->validate($request, [
            'search' => 'string',
            'hiring_mode' => 'in:PJ,CLT,BOTH',
            'occupation' => 'in:BACK,FRONT,FULL',
            'is_home_office' => 'bool',
            'city_id' => 'required_unless:is_home_office, 1',
            'salary' => 'in:asc,desc',
            'created_at' => 'in:asc,desc',
            'page' => 'required|integer',
            'per_page' => 'required|integer'
        ]);

        $search = $request->input('search');
        $hiringMode = $request->input('hiring_mode');
        $occupation = $request->input('occupation');
        $isHomeOffice = $request->input('is_home_office');
        $cityId = $request->input('city_id');
        $salary = $request->input('salary');
        $createdAt = $request->input('created_at');
        $page = $request->input('page');
        $perPage = $request->input('per_page');

        $result = $this->vacancyService->getVacanciesList(
            $search,
            $hiringMode,
            $occupation,
            $isHomeOffice,
            $cityId,
            $salary,
            $createdAt,
            $page,
            $perPage
        );

        return response()->json($result, 200);
    }
}
