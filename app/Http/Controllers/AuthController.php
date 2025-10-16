<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login fail',
                'status' => 'error'
            ], 422);
        }
        return response()->json($response, 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function profile(Request $request): JsonResponse | JsonResource
    {

        $user = $this->authService->getUser($request);

        return response()->json(
            [
                'message' => 'User retrieved successfully',
                'status' => 'success',
                'data' => new UserResource($user)
            ],
            Response::HTTP_OK
        );
    }

    public function adminCheck(Request $request): JsonResponse
    {
        return response()->json([
            'is_admin' => true,
            'message' => 'Admin access granted'
        ], 200);
    }
}
