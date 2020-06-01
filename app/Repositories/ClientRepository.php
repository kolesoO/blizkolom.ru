<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return Client::class;
    }

    /**
     * @param array $attributes
     * @return Client
     */
    public function createModel(array $attributes = []): Client
    {
        return new Client($attributes);
    }

    /**
     * @param int $id
     * @return Client|null
     */
    public function find(int $id): ?Client
    {
        /** @var Client|null $result */
        $result = Client::query()->find($id);

        return $result;
    }

    /**
     * @param Client $entity
     * @param array $options
     * @return bool
     */
    public function save(Client $entity, array $options = []): bool
    {
        return $entity->save($options);
    }

    /**
     * @param string $login
     * @return Client|null
     */
    public function getByLogin(string $login): ?Client
    {
        /** @var Client|null $result */
        $result = Client::query()
            ->where('login', $login)
            ->first();

        return $result;
    }

    /**
     * @param Client $entity
     * @param string $hash
     * @return bool
     */
    public function updateToken(Client $entity, string $hash): bool
    {
        $entity->api_token = $hash;

        return $this->save($entity);
    }
}
