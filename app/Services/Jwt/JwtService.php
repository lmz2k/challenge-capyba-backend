<?php


namespace App\Services\Jwt;

use Firebase\JWT\JWT;

class JwtService implements JwtServiceInterface
{
    /**
     * @param $object
     * @return string
     */
    public function create($object): string
    {
        return JWT::encode($object, env('JWT_KEY'));
    }

    /**
     * @param $jwt
     * @return object
     */
    public function decode($jwt): object
    {
        return JWT::decode($jwt, env('JWT_KEY'), ['HS256']);
    }
}
