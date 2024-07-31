<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataout extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'date',
        'time',
        'nik',
        'code',
        'qty',
        'pic',
        'remarks',
    ];
}
