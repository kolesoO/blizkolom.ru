<?php

declare(strict_types=1);

namespace App\Resources;

use App\Resources\Concerns\HasArrayResource;
use Illuminate\Http\Resources\Json\Resource;

/**
 * @property-read int $company_id
 * @property-read string $company_name
 * @property-read string $name
 * @property-read string $phone
 */
class CallBack extends Resource
{
    use HasArrayResource;
}
