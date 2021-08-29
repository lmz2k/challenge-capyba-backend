<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Mail\MailServiceInterface;

class AuthService implements AuthServiceInterface
{
    private FtpServiceInterface $ftpService;
    private AuthRepositoryInterface $authRepository;
    private MailServiceInterface $mailService;
    private HashServiceInterface $hashService;
    private JwtServiceInterface $jwtService;

    public function __construct(
        FtpServiceInterface $ftpService,
        AuthRepositoryInterface $authRepository,
        MailServiceInterface $mailService,
        HashServiceInterface $hashService,
        JwtServiceInterface $jwtService
    ) {
        $this->ftpService = $ftpService;
        $this->authRepository = $authRepository;
        $this->mailService = $mailService;
        $this->hashService = $hashService;
        $this->jwtService = $jwtService;
    }

    public function register($name, $email, $password, $photo): array
    {
        $passwordHash = $this->hashService->create($password);
        $photoPath = $this->ftpService->uploadFile($photo);

        $user = $this->authRepository->register($name, $email, $passwordHash, $photoPath);

        $code = $this->generateConfirmationCode();
        $token = $this->registerCodeAndSendByEmail($code, $user);

        return [
            'user' => $user,
            'token_to_validate_code' => $token
        ];
    }

    private function registerCodeAndSendByEmail($code, $user)
    {
        $email = $user->email;
        $name = $user->name;
        $codeHash = $this->hashService->create($code);
        $jwt = $this->jwtService->create(['id' => $user->id]);

        dd($jwt);
        $this->mailService->sendConfirmationCode($code, $email, $name);
        $this->registerCodeValidation($codeHash,);
    }

    private function generateConfirmationCode(): int
    {
        return random_int(10000, 999999);
    }
}
