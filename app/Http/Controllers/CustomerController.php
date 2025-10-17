<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * @OA\Get(
     *     path="/customers",
     *     tags={"Customers"},
     *     summary="Listar clientes",
     *     description="Retorna uma lista paginada de clientes com opção de busca",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Termo de busca",
     *         required=false,
     *         @OA\Schema(type="string", example="João")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clientes listados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="customers", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="pagination", type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=5),
     *                     @OA\Property(property="per_page", type="integer", example=15),
     *                     @OA\Property(property="total", type="integer", example=75)
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
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');

            if ($search) {
                $customers = $this->customerService->searchCustomers($search, $perPage);
            } else {
                $customers = $this->customerService->getAllCustomers($perPage);
            }

            return ApiResponse::success('customers.list.success', [
                'customers' => CustomerResource::collection($customers->items()),
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'per_page' => $customers->perPage(),
                    'total' => $customers->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('customers.list.error');
        }
    }

    /**
     * @OA\Post(
     *     path="/customers",
     *     tags={"Customers"},
     *     summary="Criar cliente",
     *     description="Cria um novo cliente",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="customer", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="João Silva"),
     *                     @OA\Property(property="email", type="string", example="joao@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());
            return ApiResponse::success('customers.create.success', [
                'customer' => new CustomerResource($customer)
            ], 201);
        } catch (\Exception $e) {
            return ApiResponse::error('customers.create.error', [], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->getCustomerById($id);

            if (!$customer) {
                return ApiResponse::error('customers.show.not_found', [], 404);
            }

            return ApiResponse::success('customers.show.success', [
                'customer' => new CustomerResource($customer)
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('customers.show.error');
        }
    }

    public function update(CustomerRequest $request, int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->updateCustomer($id, $request->validated());

            if (!$customer) {
                return ApiResponse::error('customers.update.not_found', [], 404);
            }

            return ApiResponse::success('customers.update.success', [
                'customer' => new CustomerResource($customer)
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('customers.update.error', [], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->customerService->deleteCustomer($id);

            if (!$deleted) {
                return ApiResponse::error('customers.delete.not_found', [], 404);
            }

            return ApiResponse::success('customers.delete.success');
        } catch (\Exception $e) {
            return ApiResponse::error('customers.delete.error');
        }
    }
}
