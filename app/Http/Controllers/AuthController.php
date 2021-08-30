<?php


namespace App\Http\Controllers;

use App\Exceptions\AlreadyVerified;
use App\Exceptions\NotVerifiedException;
use App\Exceptions\WrongCodeException;
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
                    201
                );
            }
        }
    }

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

            DB::beginTransaction();
            $result = $this->authService->login($email, $password);
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

            if ($e instanceof NotVerifiedException) {
                return response()->json(
                    [
                        'message' => 'Email not verified, new code has been sent to email',
                        'token_to_validate_code' => $e->getMessage(),
                    ],
                    403
                );
            }

            if ($e instanceof NotVerifiedException) {
                return response()->json(
                    ['message' => 'Wrong password'],
                    403
                );
            }
        }
    }

    public function confirmCode(Request $request): JsonResponse
    {
        try {
            $this->validate($request, ['code' => 'integer|required']);

            $code = $request->input('code');
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);

            $result = $this->authService->confirmCode($token, $code);
            return response()->json($result, 201);
        } catch (\Exception $e) {
            if ($e instanceof WrongCodeException) {
                return response()->json(
                    ['message' => 'Code is different'],
                    403
                );
            }
        }
    }

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
