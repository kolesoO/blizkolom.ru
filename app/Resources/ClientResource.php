<?php

declare(strict_types=1);

namespace App\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read string $name
 * @property-read string $email
 * @property-read string $callback_email
 * @property-read string $login
 * @property-read string $password
 * @property-read Collection $companies
 */
class ClientResource extends JsonResource
{
}
