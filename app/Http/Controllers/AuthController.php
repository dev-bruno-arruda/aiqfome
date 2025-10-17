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
use OpenApi\Annotations as OA;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Realizar login",
     *     description="Autentica um usuário e retorna um token de acesso",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abc123..."),
     *                 @OA\Property(property="role", type="string", example="admin")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Realizar logout",
     *     description="Revoga o token de acesso do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);
        return ApiResponse::success('auth.logout.success');
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Authentication"},
     *     summary="Obter perfil do usuário",
     *     description="Retorna os dados do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil recuperado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Admin User"),
     *                     @OA\Property(property="email", type="string", example="admin@example.com"),
     *                     @OA\Property(property="role", type="string", example="admin")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $this->authService->getUser($request);
        return ApiResponse::success('auth.profile.retrieved', [
            'user' => new UserResource($user)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin-check",
     *     tags={"Authentication"},
     *     summary="Verificar acesso de administrador",
     *     description="Verifica se o usuário autenticado tem permissão de administrador",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Acesso de administrador concedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="result", type="boolean", example=true),
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acesso negado - usuário não é administrador"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function adminCheck(Request $request): JsonResponse
    {
        return ApiResponse::boolean(true, 'auth.admin.access_granted');
    }

    /**
     * @OA\Get(
     *     path="/token-status",
     *     tags={"Authentication"},
     *     summary="Verificar status do token",
     *     description="Retorna informações sobre o token de acesso atual",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Status do token verificado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="expires_at", type="string", format="date-time", example="2024-11-15T23:00:00.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-16T23:00:00.000000Z"),
     *                 @OA\Property(property="days_until_expiry", type="integer", example=30)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function tokenStatus(Request $request): JsonResponse
    {
        $tokenInfo = $this->authService->getTokenInfo($request);
        return ApiResponse::success('auth.token.status', $tokenInfo);
    }
}
