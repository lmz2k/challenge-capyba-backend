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

    /**
     * @OA\Post(
     *     tags={"Perfil (Rota autenticada)"},
     *     path="/api/profile/",
     *     description="EP para alterar dados de um usuario logado",
     *     security={{ "apiAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Novo email do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Novo nome do usuario",
     *                 ),
     *                  @OA\Property(
     *                     property="photo",
     *                     type="file",
     *                     description="Nova foto de perfil do funcionário",
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *    )
     * )
     */
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

            $userId = $request->user->id;

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

    /**
     * @OA\Post(
     *     tags={"Perfil (Rota autenticada)"},
     *     path="/api/profile/password",
     *     description="EP para alterar senha de um usuario",
     *     security={{ "apiAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="current_password",
     *                     type="string",
     *                     description="Senha atual do usuario",
     *                 ),
     *                 @OA\Property(
     *                     property="new_password",
     *                     type="string",
     *                     description="Nova senha do usuario",
     *                 ),
     *                 required={"current_password", "current_password"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *    )
     * )
     */
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

            $userId = $request->user->id;

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
