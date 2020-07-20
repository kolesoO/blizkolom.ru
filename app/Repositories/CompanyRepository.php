<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

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
     * @param array $attributes
     * @return Company
     */
    public function create(array $attributes = []): Company
    {
        return new Company($attributes);
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
     * @param array $options
     * @return bool
     */
    public function save(Company $entity, array $options = []): bool
    {
        return $entity->save($options);
    }

    /**
     * @param Company $entity
     * @return bool
     */
    public function delete(Company $entity): bool
    {
        try {
            return $entity->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }

    /**
     * @param Client $client
     * @return Collection|Company[]
     */
    public function getByClient(Client $client): Collection
    {
        return $client->companies()
            ->get();
    }
}
