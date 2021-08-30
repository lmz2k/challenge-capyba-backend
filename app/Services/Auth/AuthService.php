<?php

namespace App\Services\Auth;

use App\Exceptions\AlreadyVerified;
use App\Exceptions\NotVerifiedException;
use App\Exceptions\WrongPasswordException;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Mail\MailServiceInterface;
use Carbon\Carbon;

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
        $this->validateEmailNotVerified($user);

        $jwt = $this->generateToken($user);

        return [
            'user' => $user,
            'token' => $jwt,
        ];
    }

    /**
     * @param $email
     * @return array
     * @throws AlreadyVerified
     */
    public function resendCode($email): array
    {
        $user = $this->userRepository->findUserByEmail($email);

        $this->validateEmailAlreadyVerified($user);
        $this->invalidateOldCodes($user);
        $token = $this->registerCodeAndSendByEmail($user);

        return [
            'user' => $user,
            'token_to_validate_code' => $token
        ];
    }

    private function generateToken($user)
    {
        $object = $user->toArray();
        $jwt = $this->jwtService->create($object);

        $this->authRepository->trackToken($jwt);

        return $jwt;
    }

    private function validateEmailAlreadyVerified($user): bool
    {
        if ($user->verified) {
            throw new AlreadyVerified();
        }
        return $user->verified;
    }

    private function validateEmailNotVerified($user): bool
    {
        if (!$user->verified) {
            $this->invalidateOldCodes($user);
            $newJwt = $this->registerCodeAndSendByEmail($user);
            throw new NotVerifiedException($newJwt);
        }
        return $user->verified;
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
        $jwt = $this->jwtService->create(
            [
                'id' => $userId,
                'created_at' => Carbon::now()
            ]
        );

        $this->mailService->sendConfirmationCode($code, $email, $name);
        $this->authRepository->registerCodeValidation($userId, $jwt, $codeHash);

        return $jwt;
    }

    private function generateConfirmationCode(): int
    {
        return random_int(10000, 999999);
    }
}
