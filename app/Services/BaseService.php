<?php

namespace App\Services;

use App\Contracts\BaseServiceInterface;
use App\Contracts\QueryFilterInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService implements BaseServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected Model $model
    )
    {}
    
    public function allPaginatedFromFilter(QueryFilterInterface $filter): LengthAwarePaginator
    {
        $filteredQuery = $filter->apply(
            $this->model->newQuery()
        );

        return $filteredQuery->paginate();
    }

    public function all(): array|Collection
    {
        return $this->model->all();
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function update(array $data, int $id): ?object
    {
        $model = $this->model->find($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): void
    {
        $this->model->destroy($id);
    }

    public function findRatesByCurrency(int $currency_id): Collection
    {
        return $this->model->where('currency_id', $currency_id)->get();
    }

}
