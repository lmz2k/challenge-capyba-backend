<?php


namespace App\Repositories\Auth;


use App\Models\RegisterConfirm;
use App\Models\TokenTacking;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{

    public function register($name, $email, $passwordHash, $photoPath): User
    {
        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = $passwordHash;
        $user->photo = $photoPath;
        $user->verified = 0;

        $user->save();

        return $user;
    }

    public function registerCodeValidation($userId, $codeHash, $token): RegisterConfirm
    {
        $registerConfirm = new RegisterConfirm();

        $registerConfirm->user_id = $userId;
        $registerConfirm->token = $token;
        $registerConfirm->code_hash = $codeHash;
        $registerConfirm->save();

        return $registerConfirm;
    }

    public function invalidateOldCodes($userId)
    {
        $codes = RegisterConfirm::where('user_id', $userId)->get();

        $codesIds = $codes->pluck('id');

        RegisterConfirm::destroy($codesIds);
    }

    public function trackToken($jwt): TokenTacking
    {
        $tokenTracking = new TokenTacking();

        $tokenTracking->token = $jwt;
        $tokenTracking->save();

        return $tokenTracking;
    }
}
