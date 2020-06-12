<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property string $name
 * @property string $code
 * @property bool $active
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $h1
 * @property string $preview_text
 * @property string $detail_text
 * @property int $preview_picture
 * @property int $detail_picture
 * @property string $contacts
 * @property string $url
 * @property string $phone
 * @property string $email
 * @property string $map_coords
 * @property Carbon $open_from
 * @property Carbon $open_to
 * @property int $ranging
 * @property-read int $client_id
 */
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
        'url', 'phone', 'email', 'map_coords', 'open_from', 'open_to', 'ranging',
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
     * @return BelongsTo
     */
    public function preview_picture(): BelongsTo
    {
        return $this->belongsTo(File::class, 'id', 'preview_picture');
    }

    /**
     * @return BelongsTo
     */
    public function detail_picture(): BelongsTo
    {
        return $this->belongsTo(File::class, 'id', 'detail_picture');
    }

    /**
     * @return MorphMany
     */
    public function options(): MorphMany
    {
        return $this->MorphMany(Options::class, 'entity');
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(CompanyGallery::class, 'company_id');
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
        $result = 0;
        $this->reviews->each(function(Review $item) use (&$result) {
            $result += $item->rating;
        });

        if ($result > 0) {
            $result = $result / $this->reviews->count();
        } else {
            $result = 3;
        }

        $result = round($result, 1);

        $str = 'bad';

        if ($result >= 4) {
            $str = 'good';
        } elseif ($result >= 3) {
            $str = 'normal';
        }

        return [
            'rating' => number_format($result, 1),
            'info' => $str,
        ];
    }
}
