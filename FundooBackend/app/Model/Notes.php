<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = [
        'title','decription' ,'userid','color','ispinned','isarchive','reminder',
    ];
}

