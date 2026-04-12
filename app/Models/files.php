<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    //

    protected $fillable = [
        "title",
        "desc",
        "path",
        "type"
    ];
}
