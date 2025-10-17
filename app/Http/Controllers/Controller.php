<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="AIQFome API",
 *     version="1.0.0",
 *     description="API para sistema de produtos favoritos",
 *     @OA\Contact(
 *         email="admin@aiqfome.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Servidor de desenvolvimento"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Token de autenticação Bearer"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints de autenticação"
 * )
 * 
 * @OA\Tag(
 *     name="Customers",
 *     description="Gerenciamento de clientes"
 * )
 * 
 * @OA\Tag(
 *     name="Favorites",
 *     description="Gerenciamento de produtos favoritos"
 * )
 * 
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
 *     @OA\Property(property="status", type="string", enum={"success", "error"}),
 *     @OA\Property(property="data", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
 *     @OA\Property(property="status", type="string", enum={"error"}),
 *     @OA\Property(property="errors", type="object")
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}