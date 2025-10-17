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
