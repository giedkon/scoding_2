<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    protected $fillable = [
        'OWM_ID', 'name', 'latest_temp', 'latest_speed', 'latest_direction', 'warning'
    ];
}
