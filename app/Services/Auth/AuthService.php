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

    public function register($name, $email, $password, $photo)
    {
        $passwordHash = Hash::make($password);
        $photoPath = $this->ftpService->uploadFile($photo);

        $user = $this->authRepository->register(
            $name,
            $email,
            $passwordHash,
            $photoPath
        );

        $this->mailService->sendConfirmationCode(1234, $email, $name);
    }
}
