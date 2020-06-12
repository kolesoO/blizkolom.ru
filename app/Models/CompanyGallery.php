<?php
declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property-read int $company_id
 * @property-read int $file_id
 */
class CompanyGallery extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

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
    protected $table = "company_gallery";
}
