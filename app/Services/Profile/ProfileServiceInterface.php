<?php


namespace App\Services\Profile;


interface ProfileServiceInterface
{
    public function update($userId, $name, $email, $photo);
    public function changePassword($userId, $currentPassword, $newPassword);
}
