<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Resources\Api\V1\User\PermissionResource;
use App\Models\User\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Permission::class);
    }

    public function index()
    {
        return Permission::all()->toResourceCollection(PermissionResource::class);
    }


}
