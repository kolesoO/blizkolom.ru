<?php

namespace App\Models;

/**
 * Class ServiceMenu
 *
 * @property int $id
 * @property bool $active
 * @property string $name
 * @property string $code
 * @property string $description
 * @property string $content
 */
class ServiceMenu extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code', 'description', 'content'
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
        'id', 'name', 'code', 'description', 'content'
    ];

    /**
     * @var string
     */
    protected $table = "service_menu";

    /** @inheritDoc */
    protected $casts = [
        'active' => 'bool'
    ];
}
