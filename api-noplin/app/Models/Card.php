<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends BaseModel
{

    protected $table = 'card';

    protected $fillable = [
        'name',
        'user_id',
        'parent_id',
        'sort_id',
        'level',
        'body',
        'logo',
        'cover',
        'custom_data',
    ];

    protected $casts = [
        'custom_data' => 'json',
    ];

    /**
     * @return HasMany
     */
    public function fieldList(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}
