<?php


namespace App\Http\Middleware;

use App\Models\RegisterConfirm;
use App\Services\Jwt\JwtServiceInterface;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UnexpectedValueException;

class CodeAuthenticator
{

    private JwtServiceInterface $jwtService;

    public function __construct(JwtServiceInterface $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle($request, Closure $next)
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

            RegisterConfirm::where('token', $token)->firstOrFail();

            return $next($request);
        } catch (\Exception $e) {
            $message = 'Wrong JWT';

            if ($e instanceof ModelNotFoundException) {
                $message = 'Expired JWT';
            }

            if($e instanceof UnexpectedValueException){
                $message = 'Unauthorized';
            }

            return response()->json(['message' => $message], 403);
        }
    }

}
