<?php


namespace App\Http\Middleware;

use App\Models\RegisterConfirm;
use App\Services\Jwt\JwtServiceInterface;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            $token = str_replace('Bearer ', '', $authorizationHeader);

            if (strlen($token) === 0) {
                return response()->json(['message' => 'Missing token'], 403);
            }
            $validToken = $this->jwtService->validate($token);

            if (!$validToken) {
                return response()->json(['message' => 'Invalid token'], 403);
            }


            RegisterConfirm::where('code_hash', $token)->firstOrFail();

            return $next($request);
        } catch (\Exception $e) {
            $message = 'Wrong JWT';

            if ($e instanceof ModelNotFoundException) {
                $message = 'Expired code';
            }

            return response()->json(['message' => $message], 403);
        }
    }

}
