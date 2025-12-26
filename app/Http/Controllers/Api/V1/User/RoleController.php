<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Resources\Api\V1\User\RoleResource;
use App\Models\User\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/roles",
     *     summary="Get roles",
     *     tags={"Role"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="get roles successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="admin"),
     *                  @OA\Property(property="description", type="string", example="role admin"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *         @OA\Response(
     *          response=403,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function index()
    {
        return Role::all()->toResourceCollection(RoleResource::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/roles/{role}",
     *     summary="Get role",
     *     tags={"Role"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="get role successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="admin"),
     *                  @OA\Property(property="description", type="string", example="role admin"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *         @OA\Response(
     *          response=403,
     *          description="Unauthorized",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function show(Role $role)
    {
        return $role->toResource(RoleResource::class);
    }

}
