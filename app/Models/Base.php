<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    /** @var array */
    protected static $available = [];

    /**
     * @param string $code
     * @param bool $isFullName
     * @return string
     */
    public function getField(string $code, bool $isFullName): string
    {
        return $isFullName ? $this->table.".".$code : $code;
    }

    /**
     * @param string $key
     * @return bool
     */
    protected static function isAvailableField(string $key): bool
    {
        return in_array($key, self::$available);
    }
}
