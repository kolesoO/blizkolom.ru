<?php

namespace App\Models;

/**
 * Class SingleText
 *
 * @property    string  $id
 * @property    string  $code
 * @property    string  $description
 * @property    string  $value
 * @package App\Models
 */
class SingleText extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'code', 'description', 'value'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected static $available = ['id', 'code', 'description', 'value'];

    /**
     * @var string
     */
    protected $table = "service_single_text";
}
