<?php


namespace App\Repositories\User;


interface UserRepositoryInterface
{
    public function findUserByAttribute($attribute, $value);

    public function updateUser($id, $attributes);
}
