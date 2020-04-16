<?php

namespace App\Models;

class Property extends Base
{
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
