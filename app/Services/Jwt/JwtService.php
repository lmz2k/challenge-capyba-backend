<?php


namespace App\Services\Jwt;

use Firebase\JWT\JWT;

class JwtService implements JwtServiceInterface
{

    public function create($object): string
    {
        return JWT::encode($object, env('JWT_KEY'));
    }

    public function validate($jwt): object
    {
        return JWT::decode($jwt, env('JWT_KEY'));
    }
}
