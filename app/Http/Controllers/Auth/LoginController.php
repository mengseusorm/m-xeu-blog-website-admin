<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\ActivityLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public ActivityLoggerService $activityLogger;

    public function __construct(ActivityLoggerService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public function login(Request $request): JsonResponse
    { 
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'errors' => $validator->errors()
            ], 422);
        }
 

        if (!Auth::guard('web')->attempt($request->only('email', 'password',))) {
            return new JsonResponse([
                'errors' => ['validation' => 'credentials_invalid']
            ], 401);
        }

        // Get authenticated user and create token
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log successful login activity
        $this->activityLogger->logAuthentication('logged in', $user, [
            'guard' => 'web',
            'remember' => $request->get('remember', false),
        ]);

        return new JsonResponse([
            'message' => 'login_success',
            'token'   => $token,
            'user'    => new UserResource($user),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Log logout activity before token deletion
        if ($user) {
            $this->activityLogger->logAuthentication('logged out', $user, [
                'guard' => 'web',
            ]);
        }
        
        $request->user()->currentAccessToken()->delete();
        return new JsonResponse([
            'message' => trans('all.message.logout_success')
        ], 200);
    }
}
