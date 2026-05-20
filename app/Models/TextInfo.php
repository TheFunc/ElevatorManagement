<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextInfo extends Model
{
    protected $table = 'text_infos';
    
    protected $fillable = [
        'TextType',
        'TextGroup',
        'TextContent'
    ];
}
