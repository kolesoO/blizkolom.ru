<?php
namespace App\Service\CacheSystem;

use Illuminate\Support\Facades\Cache;

class General
{
    public static $serviceName = "cacheSystem";

    public $resource;

    /**
     * General constructor.
     * @param string $driver
     */
    public function __construct(string $driver)
    {
        $className = __NAMESPACE__."\\Src\\".ucfirst($driver);
        $driverResource = new $className;
        if ($driverResource instanceof Cache) {
            $this->resource = $driverResource;
        }
    }

}