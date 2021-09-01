<?php


namespace App\Repositories\Auth;


use App\Models\RegisterConfirm;
use App\Models\TokenTacking;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{

    /**
     * @param $name
     * @param $email
     * @param $passwordHash
     * @param $photoPath
     * @return User
     */
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

    /**
     * @param $userId
     * @param $codeHash
     * @param $token
     * @return RegisterConfirm
     */
    public function registerCodeValidation($userId, $codeHash, $token): RegisterConfirm
    {
        $registerConfirm = new RegisterConfirm();

        $registerConfirm->user_id = $userId;
        $registerConfirm->token = $token;
        $registerConfirm->code_hash = $codeHash;
        $registerConfirm->save();

        return $registerConfirm;
    }

    /**
     * @param $userId
     */
    public function invalidateOldCodes($userId)
    {
        $codes = RegisterConfirm::where('user_id', $userId)->get();

        $codesIds = $codes->pluck('id');

        RegisterConfirm::destroy($codesIds);
    }

    /**
     * @param $jwt
     * @return TokenTacking
     */
    public function trackToken($jwt): TokenTacking
    {
        $tokenTracking = new TokenTacking();

        $tokenTracking->token = $jwt;
        $tokenTracking->save();

        return $tokenTracking;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getRegisterConfirmFromToken($token)
    {
        return RegisterConfirm::where('token', $token)->firstOrFail();
    }

    public function logout($token)
    {
        $tokenTracking = TokenTacking::where('token', $token)->get();

        $tokenTrackingIds = $tokenTracking->pluck('id');

        TokenTacking::destroy($tokenTrackingIds);

    }
}
