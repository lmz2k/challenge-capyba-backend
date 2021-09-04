<?php


namespace App\Http\Controllers;

use App\Exceptions\AlreadyVerified;
use App\Exceptions\NotVerifiedException;
use App\Exceptions\WrongCodeException;
use App\Exceptions\WrongPasswordException;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/register",
     *     description="EP para dadastro de novo usuário no sistema.",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Nome do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Senha do usúario",
     *                 ),
     *                @OA\Property(
     *                     property="photo",
     *                     type="file",
     *                     description="Foto de perfil do funcionário",
     *                 ),
     *                 required={"name", "email", "password", "photo"},
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
    public function register(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'name' => 'string|required',
                    'email' => 'email|required',
                    'password' => 'string|required',
                    'photo' => 'mimes:jpeg,bmp,png,jpg|required',
                ]
            );
            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');
            $photo = $request->file('photo');

            DB::beginTransaction();
            $result = $this->authService->register($name, $email, $password, $photo);
            DB::commit();

            return response()->json($result, 201);
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return response()->json(
                    ['message' => 'Email already registered'],
                    401
                );
            }
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/login",
     *     description="EP para logar no sistema",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Senha do usúario",
     *                 ),
     *                 required={"email", "password"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'email' => 'email|required',
                    'password' => 'string|required',
                ]
            );

            $email = $request->input('email');
            $password = $request->input('password');

            $result = $this->authService->login($email, $password);

            return response()->json($result, 200);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(
                    ['message' => 'Email not registered on system'],
                    404
                );
            }

            if ($e instanceof NotVerifiedException) {
                return response()->json(
                    [
                        'message' => 'Email not verified, new code has been sent to email',
                        'token_to_validate_code' => $e->getMessage(),
                    ],
                    403
                );
            }

            if ($e instanceof WrongPasswordException) {
                return response()->json(
                    ['message' => 'Wrong password'],
                    403
                );
            }
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/logout",
     *     description="EP para deslogar no sistema",
     *     security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
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
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        $this->authService->logout($token);

        return response()->json(['message' => 'successful logged out'], 200);
    }

    /**
     * @OA\Get(
     *     tags={"Autorização"},
     *     path="/api/auth/privacy/policy",
     *     description="Termos de uso e politica de privacidade em PDF",
     *      @OA\Response(
     *          response=200,
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
    public function privacyPolicy()
    {
        return $this->authService->privacyPolicy();
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/code/confirm",
     *     description="EP para confirmar codigo recebido por email",
     *     security={{ "codeAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="code",
     *                     type="integer",
     *                     description="Cádigo recebido por email",
     *                 ),
     *                 example={"code": 123456},
     *                 required={"code"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function confirmCode(Request $request): JsonResponse
    {
        try {
            $this->validate($request, ['code' => 'integer|required']);

            $code = $request->input('code');
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);

            $result = $this->authService->confirmCode($token, $code);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            if ($e instanceof WrongCodeException) {
                return response()->json(
                    ['message' => 'Code is different'],
                    403
                );
            }
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/code/resend",
     *     description="EP para reenviar codigo de verificação por email",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="email",
     *                     description="Email para reenviar codigo de ativação de conta",
     *                 ),
     *                 required={"email"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function resendCode(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                ['email' => 'email|required']
            );

            $email = $request->input('email');

            DB::beginTransaction();
            $result = $this->authService->resendCode($email);
            DB::commit();

            return response()->json($result, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e instanceof ModelNotFoundException) {
                return response()->json(
                    ['message' => 'Email not registered on system'],
                    404
                );
            }

            if ($e instanceof AlreadyVerified) {
                return response()->json(
                    ['message' => 'Email already verified on system'],
                    401
                );
            }
        }
    }
}
