<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $inputs = $request->all();
        if ($request->has('profile_photo_path')){
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
        return $user->toResource(UserResource::class)->additional(['token' => $token->plainTextToken]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('mobile',$request->mobile)->first();
        if (!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['data' => [],'message' => 'data invalid'])->setStatusCode(401);
        }
        $user->tokens()->delete();
        $token = $user->createToken('api-token');
        return $user->toResource(UserResource::class)->additional(['token' => $token->plainTextToken]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['data' => [],'message' => 'logout successful']);
    }
}
