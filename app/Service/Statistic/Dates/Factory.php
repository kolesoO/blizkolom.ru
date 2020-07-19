<?php

declare(strict_types=1);

namespace App\Service\Statistic\Dates;

use App\Contracts\Service\Statistic\DatesInterface;
use App\Service\Statistic\Dates\Support\Week;
use ReflectionClass;
use ReflectionException;

class Factory
{
    /** @var array */
    private static $map = [
        'week' => Week::class,
    ];

    /**
     * @param string $type
     * @return DatesInterface|null
     * @throws ReflectionException
     */
    public static function create(string $type): ?DatesInterface
    {
        if (!isset(self::$map[$type])) {
            return null;
        }

        $reflection = new ReflectionClass(self::$map[$type]);

        /** @var DatesInterface $instance */
        $instance = $reflection->newInstance();

        return $instance;
    }
}
