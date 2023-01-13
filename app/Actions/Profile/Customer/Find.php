<?php

namespace App\Actions\Profile\Customer;

use App\Models\Profile\ProfileCustomer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Find
{
    public function handle(?string $query = null, int $paginate = 8): LengthAwarePaginator
    {
        $handler = empty($query)

            ? $this
            : $this->query($query);

        return $handler->paginate($paginate);
    }

    public function query(string $query): Builder
    {
        return ProfileCustomer::where(
            fn (Builder $profileQueryBuilder) => $profileQueryBuilder
                ->where('title', 'LIKE', '%'.$query.'%')
                ->orWhere('description', 'LIKE', '%'.$query.'%')

                ->orWhereHas('category',
                    fn (Builder $categoryQueryBuilder) => $categoryQueryBuilder
                        ->where('title', 'LIKE', '%'.$query.'%')
                        ->orWhere('description', 'LIKE', '%'.$query.'%')));
    }

    public function all(): Collection
    {
        return ProfileCustomer::all();
    }

    public function byId(string $key): ProfileCustomer
    {
        return ProfileCustomer::find($key);
    }

    public function show(ProfileCustomer $profileCustomer)
    {
        return $profileCustomer->with(['histories'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ProfileCustomer::limit($limit)

            ->offset($offset)
            ->get();
    }

    public function byPage(int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this->limit(
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return ProfileCustomer::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ProfileCustomer::doesntHave('profileCustomer')->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ProfileCustomer::doesntHave('profileCustomer')->limit($limit)

            ->offset($offset)
            ->get();
    }

    public function onlyRootByPage(int $perPage, int $page = 1)
    {
        $page = $page > 0 ? $page : 1;

        return $this->onlyRootLimit(
            limit: $perPage,
            offset: $page * $perPage - $perPage
        );
    }

    public function onlyRootPaginate(int $perPage): LengthAwarePaginator
    {
        return ProfileCustomer::doesntHave('profileCustomer')->paginate($perPage);
    }
}
