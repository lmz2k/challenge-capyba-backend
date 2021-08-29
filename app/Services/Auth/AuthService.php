<?php

namespace App\Services\Auth;

use App\Exceptions\NotVerifiedException;
use App\Exceptions\WrongPasswordException;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Mail\MailServiceInterface;

class AuthService implements AuthServiceInterface
{
    private FtpServiceInterface $ftpService;
    private MailServiceInterface $mailService;
    private HashServiceInterface $hashService;
    private JwtServiceInterface $jwtService;

    private AuthRepositoryInterface $authRepository;
    private UserRepository $userRepository;

    public function __construct(
        FtpServiceInterface $ftpService,
        MailServiceInterface $mailService,
        HashServiceInterface $hashService,
        JwtServiceInterface $jwtService,
        AuthRepositoryInterface $authRepository,
        UserRepository $userRepository
    ) {
        $this->ftpService = $ftpService;

        $this->mailService = $mailService;
        $this->hashService = $hashService;
        $this->jwtService = $jwtService;
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    public function register($name, $email, $password, $photo): array
    {
        $passwordHash = $this->hashService->create($password);
        $photoPath = $this->ftpService->uploadFile($photo);

        $user = $this->authRepository->register($name, $email, $passwordHash, $photoPath);
        $token = $this->registerCodeAndSendByEmail($user);

        return [
            'user' => $user,
            'token_to_validate_code' => $token
        ];
    }

    public function login($email, $password): array
    {
        $user = $this->userRepository->findUserByEmail($email);

        $this->validatePassword($user, $password);
        $this->validateVerified($user);

        $jwt = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $jwt,
        ];
    }

    private function generateToken($user)
    {
        $object = $user->toArray();
        $jwt = $this->jwtService->create($object);

        $this->authRepository->trackToken($jwt);

        return $jwt;
    }

    private function validateVerified($user): bool
    {
        if (!$user->verified) {
            $this->invalidateOldCodes($user);
            $newJwt = $this->registerCodeAndSendByEmail($user);
            throw new NotVerifiedException($newJwt);
        }
        return true;
    }

    private function validatePassword($user, $password)
    {
        $validPassword = $this->hashService->validate($user->password, $password);

        if (!$validPassword) {
            throw new WrongPasswordException();
        }

        return $validPassword;
    }

    private function invalidateOldCodes($user)
    {
        $this->authRepository->invalidateOldCodes($user->id);
    }

    private function registerCodeAndSendByEmail($user)
    {
        $email = $user->email;
        $name = $user->name;
        $userId = $user->id;

        $code = $this->generateConfirmationCode();
        $codeHash = $this->hashService->create($code);
        $jwt = $this->jwtService->create(['id' => $userId]);

        $this->mailService->sendConfirmationCode($code, $email, $name);
        $this->authRepository->registerCodeValidation($userId, $jwt, $codeHash);

        return $jwt;
    }

    private function generateConfirmationCode(): int
    {
        return random_int(10000, 999999);
    }
}
