<?php

namespace App\Models;

use Carbon\Traits\Date;

/**
 * Class ServiceSeo
 *
 * @property    string  $id
 * @property    Date  $created_at
 * @property    Date  $updated_at
 * @property    string  $name
 * @property    string  $title
 * @property    string  $description
 * @property    string  $keywords
 * @property    string  $h1
 * @property    string  $page_url
 * @package App\Models
 */
class ServiceSeo extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'created_at', 'updated_at', 'name', 'title',
        'description', 'keywords', 'h1', 'page_url'
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
    protected static $available = [
        'id', 'created_at', 'updated_at', 'name', 'title',
        'description', 'keywords', 'h1', 'page_url'
    ];

    /**
     * @var string
     */
    protected $table = "service_seo";
}
