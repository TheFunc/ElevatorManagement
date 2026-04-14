<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoInfo extends Model
{
    protected $fillable = [
        'coverPath',
        'videoPath',
        'videoType',
        'videoGroup',
        'description'
    ];
}
