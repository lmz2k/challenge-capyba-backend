<?php


namespace App\Services\Profile;


interface ProfileServiceInterface
{
    public function update($userId, $name, $email, $photo);
}
