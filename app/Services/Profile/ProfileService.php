<?php


namespace App\Services\Profile;


use App\Exceptions\WrongPasswordException;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashServiceInterface;

class ProfileService implements ProfileServiceInterface
{
    private FtpServiceInterface $ftpService;
    private ProfileRepositoryInterface $profileRepository;
    private UserRepositoryInterface $userRepository;
    private HashServiceInterface $hashService;

    public function __construct(
        FtpServiceInterface $ftpService,
        ProfileRepositoryInterface $profileRepository,
        UserRepositoryInterface $userRepository,
        HashServiceInterface $hashService
    ) {
        $this->ftpService = $ftpService;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->hashService = $hashService;
    }

    public function update($userId, $name, $email, $photo)
    {
        if ($photo) {
            $photoUrl = $this->ftpService->uploadFile($photo);
        }

        $attributes = $this->buildAttributesToUpdate($name, $email, $photoUrl ?? null);

        return $this->profileRepository->update($userId, $attributes);
    }

    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $user = $this->userRepository->findUserById($userId);
        $oldPasswordHash = $user->password;

        $samePassword = $this->hashService->validate($oldPasswordHash, $currentPassword);

        if (!$samePassword) {
            throw new WrongPasswordException();
        }

        $newPasswordHash = $this->hashService->create($newPassword);
        $this->profileRepository->changePassword($userId, $newPasswordHash);
    }

    private function buildAttributesToUpdate($name = null, $email = null, $photo = null): array
    {
        $attributes = [];

        if ($name) {
            $attributes['name'] = $name;
        }

        if ($email) {
            $attributes['email'] = $email;
        }

        if ($photo) {
            $attributes['photo'] = $photo;
        }

        return $attributes;
    }
}
