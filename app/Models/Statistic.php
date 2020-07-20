<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $type
 * @property-read int $company_id
 * @property Company $company
 */
class Statistic extends Base
{
    protected $fillable = [
        'type'
    ];

    protected $table = "statistic";

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
