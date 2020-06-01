<?php

declare(strict_types=1);

namespace App\Service\UrlGenerator\Contracts;

interface HasUrlInterface
{
    public function getCode(): ?string;

    public function getParentId(): ?int;

    public function getId(): int;
}
