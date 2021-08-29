<?php


namespace App\Services\Auth;


use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    private FtpServiceInterface $ftpService;
    private AuthRepositoryInterface $authRepository;

    public function __construct(FtpServiceInterface $ftpService, AuthRepositoryInterface $authRepository)
    {
        $this->ftpService = $ftpService;
        $this->authRepository = $authRepository;
    }

    public function register($name, $email, $password, $photo)
    {
        $passwordHash = Hash::make($password);
        $photoPath = $this->ftpService->uploadFile($photo);

        return $this->authRepository->register(
            $name,
            $email,
            $passwordHash,
            $photoPath
        );
    }
}
