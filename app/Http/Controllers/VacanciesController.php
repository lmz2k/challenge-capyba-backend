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

    public function show(Request $request): JsonResponse
    {
        $result = $this->vacancyService->show();
        return response()->json($result, 200);
    }
}
