<?php

declare(strict_types=1);

namespace App\Service\UrlGenerator;

use App\Service\UrlGenerator\Contracts\HasUrlInterface;
use Illuminate\Database\Eloquent\Collection;

class UrlGenerator
{
    /**
     * @param int|null $id
     * @param Collection|HasUrlInterface[] $entityCollection
     * @return array
     */
    protected function getParentCode(?int $id, Collection $entityCollection): array
    {
        $result = [];

        foreach ($entityCollection as $entity) {
            if ($entity->getId() !== $id || is_null($entity->getCode())) {
                continue;
            }

            $result[] = $entity->getCode();
            $result = array_merge(
                $result,
                $this->getParentCode($entity->getParentId(), $entityCollection)
            );
        }

        return $result;
    }

    /**
     * @param HasUrlInterface $entity
     * @param Collection $entityCollection
     * @param string|null $prefix
     * @return string
     */
    public function generateByCollection(HasUrlInterface $entity, Collection $entityCollection, string $prefix = null): string
    {
        $result = array_merge(
            [$entity->getCode()],
            $this->getParentCode($entity->getParentId(), $entityCollection)
        );

        if ($prefix) {
            $result[] = $prefix;
        }

        return '/' . implode('/', array_reverse($result));
    }
}
