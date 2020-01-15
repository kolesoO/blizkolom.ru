<?php
namespace App\Service\CacheSystem\Src;

use Illuminate\Support\Facades\Cache;

class File extends Cache
{
    private static $defCacheTime = 3600;

    /**
     * @param array $data
     * @return string
     */
    private static function getId(array $data): string
    {
        return md5(serialize($data));
    }

    /**
     * @param array $params
     * @param callable $processFunction
     * @param int $cacheTime
     * @return mixed
     */
    public static function get(array $params, callable $processFunction, int $cacheTime = 0)
    {
        $cacheId = self::getId($params);
        if (!self::has($cacheId)) {
            self::put($cacheId, call_user_func($processFunction), ($cacheTime <= 0 ? self::$defCacheTime : $cacheTime));
        }

        return parent::get($cacheId);
    }
}