<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;

trait HasClient
{
    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        /** @var Client $client */
        $client = Auth::guard('api')->user();

        return $client;
    }
}
