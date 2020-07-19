<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class StatisticRepository
{
    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return Statistic::class;
    }

    /**
     * @param array $attributes
     * @return Statistic
     */
    public function create(array $attributes = []): Statistic
    {
        return new Statistic($attributes);
    }

    /**
     * @param Statistic $entity
     * @param array $options
     * @return bool
     */
    public function save(Statistic $entity, array $options = []): bool
    {
        return $entity->save($options);
    }

    /**
     * @param SupportCollection $companyId
     * @return Collection|Statistic[]
     */
    public function getByCompany(SupportCollection $companyId): Collection
    {
        /** @var Collection|Statistic[] $result */
        $result = Statistic::query()
            ->whereIn('company_id', $companyId)
            ->orderBy('created_at')
            ->get();

        return $result;
    }
}
