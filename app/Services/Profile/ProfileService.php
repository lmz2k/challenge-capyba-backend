<?php


namespace App\Services\Profile;


use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Services\Ftp\FtpServiceInterface;

class ProfileService implements ProfileServiceInterface
{
    private FtpServiceInterface $ftpService;
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(FtpServiceInterface $ftpService, ProfileRepositoryInterface $profileRepository)
    {
        $this->ftpService = $ftpService;
        $this->profileRepository = $profileRepository;
    }

    public function update($userId, $name, $email, $photo)
    {
        if ($photo) {
            $photoUrl = $this->ftpService->uploadFile($photo);
        }

        $attributes = $this->buildAttributesToUpdate($name, $email, $photoUrl ?? null);

        return $this->profileRepository->update($userId, $attributes);
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
