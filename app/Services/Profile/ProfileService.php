<?php


namespace App\Services\Profile;


use App\Exceptions\WrongPasswordException;
use App\Models\User;
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

    /**
     * ProfileService constructor.
     * @param FtpServiceInterface $ftpService
     * @param ProfileRepositoryInterface $profileRepository
     * @param UserRepositoryInterface $userRepository
     * @param HashServiceInterface $hashService
     */
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

    /**
     * @param $userId
     * @param $name
     * @param $email
     * @param $photo
     * @return mixed
     */
    public function update($userId, $name, $email, $photo)
    {
        if ($photo) {
            $photoUrl = $this->ftpService->uploadFile($photo);
        }

        $attributes = $this->buildAttributesToUpdate($name, $email, $photoUrl ?? null);

        return $this->profileRepository->update($userId, $attributes);
    }

    /**
     * @param $userId
     * @param $currentPassword
     * @param $newPassword
     * @throws WrongPasswordException
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $user = $this->userRepository->findUserByAttribute(User::ID, $userId);
        $oldPasswordHash = $user->password;

        $samePassword = $this->hashService->validate($oldPasswordHash, $currentPassword);

        if (!$samePassword) {
            throw new WrongPasswordException();
        }

        $newPasswordHash = $this->hashService->create($newPassword);
        $this->profileRepository->changePassword($userId, $newPasswordHash);
    }

    /**
     * @param null $name
     * @param null $email
     * @param null $photo
     * @return array
     */
    private function buildAttributesToUpdate($name = null, $email = null, $photo = null): array
    {
        $attributes = [];

        if ($name) {
            $attributes[User::NAME] = $name;
        }

        if ($email) {
            $attributes[User::EMAIL] = $email;
        }

        if ($photo) {
            $attributes[User::PHOTO] = $photo;
        }

        return $attributes;
    }
}
