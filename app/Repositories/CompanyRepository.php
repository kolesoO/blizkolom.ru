<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return Company::class;
    }

    /**
     * @param int $id
     * @return Company|null
     */
    public function find(int $id): ?Company
    {
        /** @var Company|null $result */
        $result = Company::query()->find($id);

        return $result;
    }

    /**
     * @param Company $entity
     * @return bool
     */
    public function save(Company $entity): bool
    {
        return $entity->save();
    }

    /**
     * @param Client $client
     * @return Collection
     */
    public function getByClient(Client $client): Collection
    {
        return $client->companies()
            ->get();
    }
}
