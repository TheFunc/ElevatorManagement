<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'register',
        'FactorySerial',
        'name',
        'Model',
        'Manufacturer',
        'status',
        'Position',
        'desc',
        'Campus',
        'building',
    ];
}