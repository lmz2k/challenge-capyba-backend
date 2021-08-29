<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Mail\MailServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    private FtpServiceInterface $ftpService;
    private AuthRepositoryInterface $authRepository;
    private MailServiceInterface $mailService;

    public function __construct(
        FtpServiceInterface $ftpService,
        AuthRepositoryInterface $authRepository,
        MailServiceInterface $mailService
    ) {
        $this->ftpService = $ftpService;
        $this->authRepository = $authRepository;
        $this->mailService = $mailService;
    }

    public function register($name, $email, $password, $photo): array
    {
        $passwordHash = Hash::make($password);
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
        $codeHash = Hash::make($code);

        $this->mailService->sendConfirmationCode($code, $email, $name);
        $this->registerCodeValidation($codeHash,);
    }

    private function generateConfirmationCode(): int
    {
        return random_int(10000, 999999);
    }
}
