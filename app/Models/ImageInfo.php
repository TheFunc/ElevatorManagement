<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageInfo extends Model
{
    protected $fillable = [
        'coverPath',
        'imagePath',
        'imageType',
        'imageGroup',
        'description',
    ];
}
