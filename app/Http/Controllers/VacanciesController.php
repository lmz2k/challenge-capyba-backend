<?php


namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Vacancy\VacancyServiceInterface;

class VacanciesController extends Controller
{
    private VacancyServiceInterface $vacancyService;

    public function __construct(VacancyServiceInterface $vacancyService)
    {
        $this->vacancyService = $vacancyService;
    }

    public function getVacancies(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'search' => 'string',
                    'hiring_mode' => 'in:PJ,CLT,BOTH',
                    'occupation' => 'in:BACK,FRONT,FULL',
                    'is_home_office' => 'bool',
                    'city_id' => 'integer',
                    'salary' => 'in:asc,desc',
                    'created_at' => 'in:asc,desc',
                    'page' => 'required|integer',
                    'per_page' => 'required|integer'
                ]
            );

            $userId = $request->user ? $request->user->id : null;

            $search = $request->input('search');
            $hiringMode = $request->input('hiring_mode');
            $occupation = $request->input('occupation');
            $isHomeOffice = $request->input('is_home_office');
            $cityId = $request->input('city_id');
            $salary = $request->input('salary');
            $createdAt = $request->input('created_at');
            $page = $request->input('page');
            $perPage = $request->input('per_page');

            $result = $this->vacancyService->getList(
                $userId,
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
        } catch (Exception $e) {
            return response()->json(
                ['message' => 'Internal error'],
                500
            );
        }
    }

    public function createVacancy(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'title' => 'string|required',
                    'description' => 'string|required',
                    'salary' => 'regex:/^\d*(\.\d{2})?$/|required',
                    'is_home_office' => 'bool|required',
                    'occupation' => 'in:BACK,FRONT,FULL|required',
                    'city_id' => 'integer|required',
                    'hiring_mode' => 'in:PJ,CLT,BOTH|required',
                ]
            );

            $userId = $request->user->id;
            $title = $request->input('title');
            $description = $request->input('description');
            $salary = $request->input('salary');
            $isHomeOffice = $request->input('is_home_office');
            $occupation = $request->input('occupation');
            $cityId = $request->input('city_id');
            $hiringMode = $request->input('hiring_mode');

            $result = $this->vacancyService->createVacancy(
                $userId,
                $title,
                $description,
                $salary,
                $isHomeOffice,
                $occupation,
                $cityId,
                $hiringMode,
            );

            return response()->json($result, 201);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode = '23000') {
                return response()->json(
                    ['message' => 'Invalid city_id'],
                    422
                );
            }
        }
    }
}
