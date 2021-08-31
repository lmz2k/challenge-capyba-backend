<?php


namespace App\Repositories\User;


interface UserRepositoryInterface
{
    public function findUserByEmail($email);
    public function findUserById($id);
    public function updateUser($id, $attributes);
}
