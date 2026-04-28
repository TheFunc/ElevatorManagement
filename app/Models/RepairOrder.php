<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'path',
        'time',
        'group_id',
        'sort_order'
    ];

    protected $dates = [
        'time',
        'created_at',
        'updated_at'
    ];
}