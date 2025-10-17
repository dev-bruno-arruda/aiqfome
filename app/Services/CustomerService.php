<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function getAllCustomers(int $perPage = 15): LengthAwarePaginator
    {
        return Customer::paginate($perPage);
    }

    public function getCustomerById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function createCustomer(array $data): Customer
    {
        $this->validateEmailUniqueness($data['email']);

        return Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function updateCustomer(int $id, array $data): ?Customer
    {
        $customer = $this->getCustomerById($id);
        
        if (!$customer) {
            return null;
        }

        if (isset($data['email']) && $data['email'] !== $customer->email) {
            $this->validateEmailUniqueness($data['email']);
        }

        $customer->update($data);
        return $customer->fresh();
    }

    public function deleteCustomer(int $id): bool
    {
        $customer = $this->getCustomerById($id);
        
        if (!$customer) {
            return false;
        }

        return $customer->delete();
    }

    private function validateEmailUniqueness(string $email): void
    {
        if (Customer::where('email', $email)->exists()) {
            throw new \Exception('Email já está em uso por outro cliente.');
        }
    }

    public function searchCustomers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return Customer::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->paginate($perPage);
    }
}