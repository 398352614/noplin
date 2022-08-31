<?php

namespace App\Models;

class Field extends BaseModel
{

    protected $table = 'field';

    protected $fillable = [
        'name',
        'card_id',
        'sort_id',
        'description',
        'default',
        'type',
        'formula',
        'is_list',
        'level',
        'config'
    ];


}
