<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class Authenticate extends BaseMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return JsonResponse|Response|ResponseFactory|mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            // check if request has authorization header and get token
            $token = $this->getAuthorizationToken($request->header('Authorization'));

            $decodedToken = $this->getDecodedToken($token);

            // check if token is registered on database
            $this->validateUserTokenOnDatabase($token);

            // saving logged user on request memory
            $request->user = $this->findUserFromId($decodedToken->id);

            return $next($request);
        } catch (\Exception $e) {
            $message = 'Invalid token';

            if ($e instanceof ModelNotFoundException || $e instanceof ModelNotFoundException) {
                $message = 'Unauthorized';
            }

            return response()->json(['message' => $message], 403);
        }
    }
}
