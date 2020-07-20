<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property string $name
 * @property string $email
 * @property string $callback_email
 * @property string $login
 * @property string $password
 * @property string $api_token
 * @property Collection $companies
 */
class Client extends Base
{
    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'callback_email', 'login', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'api_token'];

    /**
     * @var array
     */
    protected static $available = [];

    /**
     * @var string
     */
    protected $table = "clients";

    /** @inheritDoc */
    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->password = Hash::make($model->password);
        });
    }

    /**
     * @return HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'client_id');
    }
}
