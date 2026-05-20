<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextType extends Model
{
    protected $table = 'text_types';
    
    protected $fillable = [
        'type'
    ];
}
