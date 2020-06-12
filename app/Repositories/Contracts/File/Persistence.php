<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\File;

interface Persistence
{
    public function persist(): void;

    public function retrieve();
}
