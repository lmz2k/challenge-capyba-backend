<?php


namespace App\Http\Controllers;

use App\Services\Auth\AuthServiceInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
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

            $result = $this->authService->register($name, $email, $password, $photo);
            return response()->json($result, 201);
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == '1062') {
                    return response()->json(
                        ['message' => 'Email already registered'],
                        201
                    );
                }
            }
        }
    }
}
