<?php

namespace App\Http\Middleware;

use App\Models\RegisterConfirm;
use App\Models\TokenTacking;
use App\models\User;
use App\Services\Jwt\JwtServiceInterface;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UnexpectedValueException;

class Authenticate
{
    private JwtServiceInterface $jwtService;

    public function __construct(JwtServiceInterface $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $authorizationHeader = $request->header('Authorization');
            $authArray = explode(' ', $authorizationHeader);

            if (count($authArray) === 1) {
                return response('Unexpected token formation (Did you forget to put Bearer before the token?)', 400);
            }

            $token = $authArray[1];

            if (strlen($token) === 0) {
                return response()->json(['message' => 'Missing token'], 403);
            }
            $validToken = $this->jwtService->validate($token);

            if (!$validToken) {
                return response()->json(['message' => 'Invalid token'], 403);
            }

            TokenTacking::where('token', $token)->firstOrFail();

            $request->user = User::find($validToken->id);
            return $next($request);

        } catch (\Exception $e) {
            $message = 'Invalid token';

            if ($e instanceof ModelNotFoundException || $e instanceof ModelNotFoundException) {
                $message = 'Unauthorized';
            }

            return response()->json(['message' => $message], 403);
        }

        return $next($request);
    }
}
