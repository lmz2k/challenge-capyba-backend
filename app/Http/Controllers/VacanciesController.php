<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VacanciesController extends Controller
{


    public function show(Request $request): JsonResponse
    {
        return response()->json(['hey' => 'tei'], 200);
    }
}
