<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Requests\Api\V1\User\UserRequest;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Http\Trait\DataFiltering;
use App\Models\User\User;
use App\Notifications\User\UserSyncRoleNotification;
use App\Notifications\User\UserUpdateNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    use DataFiltering,AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/users",
     *     summary="Get users",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="company,ownedCompany,projects,assignedProjects,tasks,assignedTasks"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get users successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                  @OA\Property(property="mobile", type="string", example="09167516826"),
     *                  @OA\Property(property="profile_photo_path", type="string", example="image/image.png"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $users = User::query();

        $this->loadingRelationFromRequest(
            model: $users, request: $request,
            includes: ['company','ownedCompany','projects','assignedProjects','tasks','assignedTasks'],
            relations: ['company','ownedCompany','projects','assignedProjects','tasks','assignedTasks']
        );

        return UserResource::collection($users->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/users/{user}",
     *     summary="Get user",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="company,ownedCompany,projects,assignedProjects,tasks,assignedTasks"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get users successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                  @OA\Property(property="mobile", type="string", example="09167516826"),
     *                  @OA\Property(property="profile_photo_path", type="string", example="image/image.png"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function show(Request $request, User $user)
    {
        $this->loadingRelationFromRequest(
            model: $user, request: $request,
            includes: ['company','ownedCompany','projects','assignedProjects','tasks','assignedTasks'],
            relations: ['company','ownedCompany','projects','assignedProjects','tasks','assignedTasks'],
            relationLoadingMode: 'load'
        );

        return UserResource::make($user);
    }

    /**
     *
     * @OA\Put  (
     *     path="/api/v1/users/{user}",
     *     summary="User update",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="User update",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="mobile",
     *                      type="string",
     *                      description="mobile number",
     *                      example="09167516826",
     *                      maxLength=11,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="first_name",
     *                      type="string",
     *                      example="milad",
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="last_name",
     *                      type="string",
     *                      example="rezai",
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                       property="profile_photo_path",
     *                       type="file",
     *                        nullable=true
     *                   ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="update project successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                   @OA\Property(property="mobile", type="string", example="09167516826"),
     *                   @OA\Property(property="profile_photo_path", type="string", example="image/image.png"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *          @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function update(UserRequest $request, User $user)
    {
        if ($request->hasFile('profile_photo_path')){
            $file = $request->file('profile_photo_path');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $save = $file->move(public_path('image/profile'),$fileName);
            $filePath = 'image/profile/'.$fileName;
            $request['profile_photo_path'] = $filePath;
        }
        $user->update($request->all());
        $user->notify(new UserUpdateNotification());
        return UserResource::make($user);
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/users/{user}",
     *     summary="delete a user",
     *     tags={"User"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="user delete successfully",
     *     ),
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *       ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/users/{user}/roles",
     *     summary="Get users with roles",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="get users successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                  @OA\Property(property="mobile", type="string", example="09167516826"),
     *                  @OA\Property(property="profile_photo_path", type="string", example="image/image.png"),
     *                             @OA\Property(property="roles", type="string", example="['admin]"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *                   @OA\Response(
     *            response=403,
     *            description="Unauthorized",
     *        ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function roles(User $user)
    {
        return $user->load('roles')->toResource(UserResource::class);
    }

    /**
     *
     * @OA\Post   (
     *     path="/api/v1/users/{user}/roles",
     *     summary="User sync roles",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="User sync roles",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="roles",
     *                      type="string",
     *                      description="roles id for sync",
     *                      example="[1,2,3,4]",
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="sync roles successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="first_name", type="string", example="milad"),
     *                   @OA\Property(property="last_name", type="string", example="rezai"),
     *                   @OA\Property(property="mobile", type="string", example="09167516826"),
     *                   @OA\Property(property="profile_photo_path", type="string", example="image/image.png"),
     *                        @OA\Property(property="roles", type="string", example="['admin]"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *              @OA\Response(
     *           response=403,
     *           description="Unauthorized",
     *       ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function syncRoles(UserRequest $request,User $user)
    {
        $roles = array_values($request->roles);
        $user->roles()->sync($roles);
        $user->notify(new UserSyncRoleNotification());

        return $user->load('roles')->toResource(UserResource::class);
    }
}
