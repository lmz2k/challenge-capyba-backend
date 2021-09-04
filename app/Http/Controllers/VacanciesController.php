<?php


namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Vacancy\VacancyServiceInterface;

class VacanciesController extends Controller
{
    private VacancyServiceInterface $vacancyService;

    /**
     * VacanciesController constructor.
     * @param VacancyServiceInterface $vacancyService
     */
    public function __construct(VacancyServiceInterface $vacancyService)
    {
        $this->vacancyService = $vacancyService;
    }

    /**
     * @OA\Get(
     *     tags={"Vagas de emprego"},
     *     path="/api/vacancy",
     *     description="Listagem de vagas de emprego",
     *  @OA\Parameter(
     *     name="hiring_mode",
     *     required=false,
     *     description="Tipo de contratação",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"BOTH", "CLT", "PJ"},
     *            default="BOTH"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="is_home_office",
     *     required=false,
     *     description="Boolean representando se é home office ou não",
     *     in="query",
     *     @OA\Schema(
     *         type="boolean"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="salary",
     *     required=false,
     *     description="Tipo de ordenação a partir do valor do salario",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="created_at",
     *     required=false,
     *     description="Tipo de ordenação a partir da data de criação da vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="search",
     *     required=false,
     *     description="Pequisa textual para encontrar vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="occupation",
     *     required=false,
     *     description="Tipo de vaga, back, front ou full stack",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"BACK", "FRONT", "FULL"},
     *            default="FULL"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="page",
     *     required=true,
     *     description="Pagina da pesquisa",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=1,
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="per_page",
     *     required=true,
     *     description="Quantidade de itens por pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=20,
     *      )
     *    ),
     *   @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *    ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *    ),
     *    @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *    ),
     *    @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *     )
     *)
     */
    /**
     * @OA\Get(
     *     tags={"Vagas de emprego"},
     *     path="/api/vacancy/my",
     *     description="Listagem de vagas de emprego criadas pelo usuario",
     *     security={{ "apiAuth": {} }},
     *  @OA\Parameter(
     *     name="hiring_mode",
     *     required=false,
     *     description="Tipo de contratação",
     *     in="query",
     *   @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"BOTH", "CLT", "PJ"},
     *            default="BOTH"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="is_home_office",
     *     required=false,
     *     description="Boolean representando se é home office ou não",
     *     in="query",
     *     @OA\Schema(
     *         type="boolean"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="salary",
     *     required=false,
     *     description="Tipo de ordenação a partir do valor do salario",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="created_at",
     *     required=false,
     *     description="Tipo de ordenação a partir da data de criação da vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="search",
     *     required=false,
     *     description="Pequisa textual para encontrar vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="occupation",
     *     required=false,
     *     description="Tipo de vaga, back, front ou full stack",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"BACK", "FRONT", "FULL"},
     *            default="FULL"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="page",
     *     required=true,
     *     description="Pagina da pesquisa",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=1,
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="per_page",
     *     required=true,
     *     description="Quantidade de itens por pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=20,
     *      )
     *    ),
     *   @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *    ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *    ),
     *    @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *    ),
     *    @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *     )
     *)
     */
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

    /**
     * @OA\Post(
     *     tags={"Vagas de emprego"},
     *     path="/api/vacancy/",
     *     description="EP para alterar senha de um usuario",
     *     security={{ "apiAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="titulo da vaga",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Descrição da vaga",
     *                 ),
     *                 @OA\Property(
     *                     property="salary",
     *                     type="number",
     *                     description="Valor da vaga",
     *                 ),
     *                 @OA\Property(
     *                     property="is_home_office",
     *                     type="boolean",
     *                     description="Boolean reprsentando se a vaga é home office",
     *                 ),
     *                @OA\Property(
     *                     property="occupation",
     *                     type="boolean",
     *                     description="Ocupação [BACK, FRONT ou FULL]",
     *                 ),
     *                @OA\Property(
     *                     property="city_id",
     *                     type="integer",
     *                     description="Id da cidade da empresa da vaga",
     *                 ),
     *                @OA\Property(
     *                     property="hiring_mode",
     *                     type="boolean",
     *                     description="Tipo de contratação [CLT, PJ ou BOTH]",
     *                 ),
     *                 required={"title", "description", "salary", "is_home_office", "occupation", "city_id", "hiring_mode"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation"
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *    )
     * )
     */
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
