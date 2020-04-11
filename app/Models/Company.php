<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Base
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
    protected $table = "companies";

    /**
     * @param int $time
     * @return array
     */
    public function openCloseTime(int $time): array
    {
        if (strtotime($this->open_from) > $time) {
            return [
                'status' => false,
                'time' => date('H:i', strtotime($this->open_from)),
                'state' => 'from',
            ];
        } else {
            return [
                'status' => strtotime($this->open_to) > $time,
                'time' => date('H:i', strtotime($this->open_to)),
                'state' => 'to',
            ];
        }
    }

    /**
     * @return array
     */
    public function getRating(): array
    {
        $str = 'bad';

        if ((float) $this->rating >= 4) {
            $str = 'good';
        } elseif ((float) $this->rating >= 3) {
            $str = 'normal';
        }

        return [
            'rating' => $this->rating,
            'info' => $str,
        ];
    }
}
