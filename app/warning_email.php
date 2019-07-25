<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class warning_email extends Model
{
    protected $fillable = [
        'email', 'location'
    ];
}
