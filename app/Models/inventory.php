<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'period',
        'date',
        'time',
        'pic',
        'qty',
        'price',
        'location',
        'category',
        'name',
        'unit',
    ];
}
