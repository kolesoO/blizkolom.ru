<?php

declare(strict_types=1);

namespace App\Resources;

use App\DTO\Statistic\StatisticList;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
{
    /** @var StatisticList */
    public $resource;
}
