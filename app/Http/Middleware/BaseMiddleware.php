<?php


namespace App\Http\Middleware;

use App\Models\RegisterConfirm;
use App\Models\TokenTacking;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\Jwt\JwtServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class BaseMiddleware
{
    private JwtServiceInterface $jwtService;
    private UserRepository $userRepository;

    /**
     * BaseMiddleware constructor.
     * @param JwtServiceInterface $jwtService
     * @param UserRepository $userRepository
     */
    public function __construct(JwtServiceInterface $jwtService, UserRepository $userRepository)
    {
        $this->jwtService = $jwtService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function getDecodedToken($token)
    {
        return $this->jwtService->decode($token);
    }

    /**
     * @param $id
     * @return mixed
     */
    protected function findUserFromId($id)
    {
        return $this->userRepository->findUserByAttribute(User::ID, $id);
    }

    /**
     * @param $header
     * @return JsonResponse|Response|ResponseFactory|mixed|string
     */
    protected function getAuthorizationToken($header)
    {
        $authorizationHeader = $header;
        $authArray = explode(' ', $authorizationHeader);

        if (count($authArray) === 1) {
            return response('Unexpected token formation (Did you forget to put Bearer before the token?)', 400);
        }

        if (strlen($authArray[1]) === 0) {
            return response()->json(['message' => 'Missing token'], 403);
        }

        return $authArray[1];
    }

    protected function validateUserTokenOnDatabase($token)
    {
        TokenTacking::where('token', $token)->firstOrFail();
    }

    /**
     * @param $token
     */
    protected function validateCodeConfirmTokenOnDatabase($token)
    {
        RegisterConfirm::where('token', $token)->firstOrFail();
    }

}
