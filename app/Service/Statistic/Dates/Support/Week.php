<?php

declare(strict_types=1);

namespace App\Service\Statistic\Dates\Support;

use App\Contracts\Service\Statistic\DatesInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Week implements DatesInterface
{
    private const DAYS_COUNT = 6;

    /** @inheritDoc */
    public function groupFromList(Collection $list, int $start = 0): Collection
    {
        $now = Carbon::now();

        return Collection::make([$now])
            ->merge(
                $this->getRangeDays(
                    Carbon::create($now->year, $now->month, $now->day)
                )
                    ->toArray()
            )
            ->reverse()
            ->values();
    }

    /** @inheritDoc */
    public function getFormatted(Collection $list): Collection
    {
        return $list->map(static function (Carbon $item) {
            return $item->format('d.m.Y');
        });
    }

    /**
     * @param Carbon $max
     * @return Collection|Carbon[]
     */
    private function getRangeDays(Carbon $max): Collection
    {
        return Collection::times(self::DAYS_COUNT, function () use ($max) {
            return clone $max->subDay();
        });
    }
}
