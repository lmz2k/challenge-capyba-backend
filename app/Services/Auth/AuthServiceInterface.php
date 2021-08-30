<?php


namespace App\Services\Auth;


interface AuthServiceInterface
{
    public function register($name, $email, $password, $photo);
    public function login($email, $password);
    public function resendCode($email);
    public function confirmCode($token, $code);
}
