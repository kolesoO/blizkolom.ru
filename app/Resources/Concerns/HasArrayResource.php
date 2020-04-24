<?php

declare(strict_types=1);

namespace App\Resources\Concerns;

trait HasArrayResource
{
    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->offsetExists($key) ? $this->offsetGet($key) : null;
    }
}
