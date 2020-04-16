<?php

namespace App\Models;

/**
 * Class File
 *
 * @property    string  $id
 * @property    string  $value
 */
class Options extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
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
    protected $table = "options";
}
