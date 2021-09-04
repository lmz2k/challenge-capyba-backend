<?php

namespace App\Http\Middleware;

use App\Exceptions\NotVerifiedException;
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

            $user = $this->findUserFromId($decodedToken->id);

            // check if user has previously confirm email

            $this->validateUserHasConfirmedByEmail($user);
            // saving logged user on request memory
            $request->user = $this->findUserFromId($decodedToken->id);

            return $next($request);
        } catch (\Exception $e) {
            $message = 'Invalid token';

            if ($e instanceof ModelNotFoundException) {
                $message = 'Unauthorized';
            }

            if ($e instanceof NotVerifiedException) {
                $message = 'You must confirm your email first';
            }

            return response()->json(['message' => $message], 403);
        }
    }
}
