<?php

namespace App\Actions\Profile\Driver;

use App\Models\Profile\ProfileDriver;
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
        return ProfileDriver::where(
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
        return ProfileDriver::all();
    }

    public function byId(string $key): ProfileDriver
    {
        return ProfileDriver::find($key);
    }

    public function show(ProfileDriver $profileDriver)
    {
        return $profileDriver->with(['histories'])->get();
    }

    public function limit(int $limit, int $offset = 0): Collection
    {
        return ProfileDriver::limit($limit)

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
        return ProfileDriver::paginate($perPage);
    }

    public function onlyRootAll(): Collection
    {
        return ProfileDriver::doesntHave('profileDriver')->get();
    }

    public function onlyRootLimit(int $limit, int $offset = 0): Collection
    {
        return ProfileDriver::doesntHave('profileDriver')->limit($limit)

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
        return ProfileDriver::doesntHave('profileDriver')->paginate($perPage);
    }
}
