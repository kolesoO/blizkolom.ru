<?php

namespace App\Models;

use App\Service\UrlGenerator\Concerns\HasUrl;
use App\Service\UrlGenerator\Contracts\HasUrlInterface;
use Carbon\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read string $title
 * @property-read int $parent_id
 * @property-read bool $filtered
 * @property-read bool $urlable
 * @property-read bool $root_url
 * @property-read string $code
 * @property-read bool $popular
 * @property-read string $genetiv
 * @property-read string $gdetiv
 * @property-read string $nominativ
 */
class Property extends Base implements HasUrlInterface
{
    use HasUrl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'parent_id', 'filtered', 'urlable',
        'root_url', 'code', 'popular', 'genetiv', 'gdetiv', 'nominativ',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected static $available = [];

    /**
     * @var string
     */
    protected $table = "properties";
}
