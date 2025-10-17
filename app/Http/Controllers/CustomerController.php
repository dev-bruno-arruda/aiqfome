<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
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

            return response()->json([
                'message' => 'Clientes listados com sucesso',
                'status' => 'success',
                'data' => CustomerResource::collection($customers->items()),
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'per_page' => $customers->perPage(),
                    'total' => $customers->total(),
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar clientes',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        try {
            $customer = $this->customerService->createCustomer($request->validated());

            return response()->json([
                'message' => 'Cliente criado com sucesso',
                'status' => 'success',
                'data' => new CustomerResource($customer)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar cliente',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->getCustomerById($id);

            if (!$customer) {
                return response()->json([
                    'message' => 'Cliente não encontrado',
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Cliente encontrado com sucesso',
                'status' => 'success',
                'data' => new CustomerResource($customer)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar cliente',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(CustomerRequest $request, int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->updateCustomer($id, $request->validated());

            if (!$customer) {
                return response()->json([
                    'message' => 'Cliente não encontrado',
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Cliente atualizado com sucesso',
                'status' => 'success',
                'data' => new CustomerResource($customer)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar cliente',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->customerService->deleteCustomer($id);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Cliente não encontrado',
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Cliente removido com sucesso',
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao remover cliente',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
