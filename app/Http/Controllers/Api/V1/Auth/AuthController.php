<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\User\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User\User;
use App\Notifications\User\LoginNotification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *
     * @OA\Post(
     *     path="/api/v1/auth",
     *     summary="Create a new user",
     *     description="Create a new user with the provided data",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "mobile"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     description="User's first name",
     *                     example="milad",
     *                     maxLength=30,
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     description="User's last name",
     *                     example="rezai",
     *                     maxLength=30,
     *                     nullable=true
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User's password",
     *                     example="secret123",
     *                     minLength=8,
     *                     maxLength=12
     *                 ),
     *                      @OA\Property(
     *                      property="password_confirmation",
     *                      type="string",
     *                      format="password",
     *                      description="User's password",
     *                      example="secret123",
     *                      minLength=8,
     *                      maxLength=12
     *                  ),
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     description="User's mobile number",
     *                     example="09167516826",
     *                 ),
     *                 @OA\Property(
     *                     property="profile_photo_path",
     *                     type="file:png,jpg,jpeg",
     *                     description="User's frofile photo",
     *                     nullable=true
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                 @OA\Property(property="last_name", type="string", example="rezai"),
     *                 @OA\Property(property="mobile", type="string", example="09167516826"),
     *                 @OA\Property(property="profile_photo_path", type="string", example="images/users/2025/12/01/1764590305.png"),
      *             ),
     *                  @OA\Property(property="token", type="string", example="2|vlLCKWNJw1tDEsGYhUY6YqS3egSdfvfKr1EXG7NM0abe24e2"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $inputs = $request->all();
        if ($request->hasFile('profile_photo_path')){
            $file = $request->file('profile_photo_path');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $save = $file->move(public_path('image/profile'),$fileName);
            $filePath = 'image/profile/'.$fileName;
            $inputs['profile_photo_path'] = $filePath;
        }
        $inputs['password'] = Hash::make($inputs['password']);
        $inputs['activation'] = 1;
        $inputs['status'] = 1;
        $user = User::create($inputs);
        $token = $user->createToken('api-token');
        event(new UserRegistered($user->id));

        return $user->toResource(UserResource::class)->additional(['token' => $token->plainTextToken]);
    }

    /**
     *
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="login a  user",
     *     description="login a  user with the provided data",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "mobile"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User's password",
     *                     example="secret123",
     *                     minLength=8,
     *                     maxLength=12
     *                 ),
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     description="User's mobile number",
     *                     example="09167516826",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                 @OA\Property(property="last_name", type="string", example="rezai"),
     *                 @OA\Property(property="mobile", type="string", example="09167516826"),
     *                 @OA\Property(property="profile_photo_path", type="string", example="images/users/2025/12/01/1764590305.png"),
     *             ),
     *                  @OA\Property(property="token", type="string", example="2|vlLCKWNJw1tDEsGYhUY6YqS3egSdfvfKr1EXG7NM0abe24e2"),
     *         )
     *     ),
     *
     *       @OA\Response(
     *          response=401,
     *          description="Invalid data",
     *     @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                        @OA\Property(property="", type="", example=""),
     *              ),
     *                   @OA\Property(property="message", type="string", example="data invalid"),
     *          )
     *      ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('mobile',$request->mobile)->first();

        if (!$user || !Hash::check($request->password,$user->password))
            throw new AuthenticationException();

        $user->tokens()->delete();
        $token = $user->createToken('api-token');
        $user->notify(new LoginNotification());

        return $user->toResource(UserResource::class)->additional(['token' => $token->plainTextToken]);
    }

    /**
     *
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="logout a  user",
     *     description="logout a  user with the token",
     *     tags={"Auth"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="User logout successfully",
     *     ),
     *          @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
