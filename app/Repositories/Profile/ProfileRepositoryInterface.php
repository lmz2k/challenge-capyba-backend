<?php


namespace App\Repositories\Profile;


interface ProfileRepositoryInterface
{
    public function update($userId, $attributes);
}
