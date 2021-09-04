<?php


namespace App\Http\Middleware;

use App\Models\RegisterConfirm;
use App\Services\Jwt\JwtServiceInterface;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UnexpectedValueException;

class CodeAuthenticator extends BaseMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // check if request has authorization header and get token
            $token = $this->getAuthorizationToken($request->header('Authorization'));

            // try to decode token to validate if is valid token on system
            $this->getDecodedToken($token);

            // check if token is registered on database
            $this->validateCodeConfirmTokenOnDatabase($token);

            return $next($request);
        } catch (\Exception $e) {
            $message = 'Wrong JWT';

            if ($e instanceof ModelNotFoundException) {
                $message = 'Expired JWT';
            }

            if ($e instanceof UnexpectedValueException) {
                $message = 'Unauthorized';
            }

            return response()->json(['message' => $message], 403);
        }
    }

}
