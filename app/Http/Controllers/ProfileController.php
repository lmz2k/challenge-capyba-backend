<?php


namespace App\Http\Controllers;


use App\Exceptions\WrongPasswordException;
use App\Services\Profile\ProfileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileServiceInterface $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'name' => 'string',
                    'email' => 'string',
                    'photo' => 'mimes:jpeg,bmp,png,jpg',
                ]
            );

            $userId = $request->user ? $request->user->id : null;

            $name = $request->input('name');
            $email = $request->input('email');
            $photo = $request->file('photo');

            $result = $this->profileService->update($userId, $name, $email, $photo);

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'Internal error'],
                500
            );
        }
    }

    public function changePassword(Request $request): JsonResponse
    {
        try {
            $this->validate(
                $request,
                [
                    'current_password' => 'string|required',
                    'new_password' => 'string|required'
                ]
            );

            $userId = $request->user ? $request->user->id : null;

            $currentPassword = $request->input('current_password');
            $newPassword = $request->input('new_password');

            $result = $this->profileService->changePassword($userId, $currentPassword, $newPassword);

            return response()->json($result, 200);
        } catch (WrongPasswordException $e) {
            return response()->json(
                ['message' => 'Incorrect current password'],
                401
            );
        }
    }

}
