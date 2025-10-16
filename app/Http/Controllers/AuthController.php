<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request): JsonResponse
    {
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
}
