<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTotal extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'qty',
        'location',
        'name',
        'unit',
    ];
}
