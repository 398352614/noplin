<?php

namespace App\Models;

class File extends BaseModel
{

    protected $table = 'file';

    protected $fillable = [
        'name',
        'user_id',
        'type',
        'path'
    ];


}
