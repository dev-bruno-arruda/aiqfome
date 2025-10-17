<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request): JsonResponse
    {
        Log::info('Login request', ['email' => $request->email, 'password' => $request->password]);
        try {
            $response = $this->authService->login($request->email, $request->password);
            return ApiResponse::success('auth.login.success', $response);
        } catch (\Exception $e) {
            return ApiResponse::error('auth.login.failed');
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);
        return ApiResponse::success('auth.logout.success');
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $this->authService->getUser($request);
        return ApiResponse::success('auth.profile.retrieved', [
            'user' => new UserResource($user)
        ]);
    }

    public function adminCheck(Request $request): JsonResponse
    {
        return ApiResponse::boolean(true, 'auth.admin.access_granted');
    }
}
