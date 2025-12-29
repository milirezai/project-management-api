<?php

namespace App\Exceptions;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\BusinessExceptions;


class ApiExceptionHandler
{
    public static function handle(Throwable $e , Request $request)
    {
        if (! $request->expectsJson())
            return null;

        if ($e instanceof ValidationException){
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => [$e->errors()]
            ])->setStatusCode(422);
        }

        if ($e instanceof AccessDeniedHttpException){
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => 'This action is unauthorized',
            ])->setStatusCode(403);
        }

        if ($e instanceof AuthorizationException){
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => $e->getMessage()
            ])->setStatusCode(403);
        }

        if ($e instanceof AuthenticationException){
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' =>   $e->getMessage()
            ])->setStatusCode(401);
        }

        if ($e instanceof BusinessExceptions){
            return response()->json([
                'status' => 'error',
                'code' => 409,
                'message' =>   $e->getMessage()
            ])->setStatusCode(409);
        }

    }
}
