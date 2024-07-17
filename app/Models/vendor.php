<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'no_tel',
        'pic',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
