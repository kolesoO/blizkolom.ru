<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'active', 'title', 'description', 'keywords',
        'h1', 'preview_text', 'detail_text', 'preview_picture', 'detail_picture', 'contacts',
        'url', 'phone', 'email', 'map_coords', 'open_from', 'open_to', 'rating',
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
    protected $table = "companies";

    /**
     * @return MorphMany
     */
    public function options(): MorphMany
    {
        return $this->MorphMany(Options::class, 'entity');
    }

    /**
     * @param int $time
     * @return array
     */
    public function openCloseTime(int $time): array
    {
        if (is_null($this->open_from) || is_null($this->open_to)) {
            return [
                'status' => false,
                'time' => null,
                'state' => null,
            ];
        }

        if ($this->open_from === '00:00:00' && $this->open_to === '23:59:59') {
            return [
                'status' => true,
                'time' => 'круглосуточно',
                'state' => 'full'
            ];
        }

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
