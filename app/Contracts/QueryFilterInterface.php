<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface QueryFilterInterface
{

    public function apply(Builder $builder): Builder;

    public function filter($arr): Builder;

    public function sort($value): void;
}
