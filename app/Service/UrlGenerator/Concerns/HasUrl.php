<?php

declare(strict_types=1);

namespace App\Service\UrlGenerator\Concerns;

/**
 * @property-read int $id
 * @property-read string|null $code
 * @property-read int|null $parent_id
 */
trait HasUrl
{
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
