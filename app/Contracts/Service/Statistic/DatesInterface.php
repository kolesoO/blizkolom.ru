<?php

declare(strict_types=1);

namespace App\Contracts\Service\Statistic;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface DatesInterface
{
    /**
     * @param Collection $list
     * @param int $start
     * @return Collection|Carbon[]
     */
    public function groupFromList(Collection $list, int $start = 0): Collection;

    /**
     * @param Collection|Carbon[] $list
     * @return Collection|Carbon[]
     */
    public function getFormatted(Collection $list): Collection;
}
