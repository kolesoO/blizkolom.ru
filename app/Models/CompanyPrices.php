<?php

namespace App\Models;

class CompanyPrices extends Base
{
    /** @var bool */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'company_id', 'property_id',
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
    protected $table = "company_prices";
}
