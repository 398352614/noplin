<?php


namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class BaseModel extends Model
{
    use SoftDeletes;

    protected $perPage = 10;

    /**
     * 占位
     * @var array
     */
    public array $dictionary = [];
    public array $filer = [];

    protected $hidden = [
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getCreatedAtAttribute(): string
    {
        return (new Carbon($this->attributes['created_at']))->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->toDateTimeString();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getUpdatedAtAttribute(): string
    {
        return (new Carbon($this->attributes['updated_at']))->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->toDateTimeString();
    }
}
