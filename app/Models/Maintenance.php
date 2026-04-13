<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_devices',
        'next_inspection_date',
        'responsible_person',
        'contact_phone',
        'remark',
        'status',
    ];

    protected $casts = [
        'next_inspection_date' => 'date',
    ];
}