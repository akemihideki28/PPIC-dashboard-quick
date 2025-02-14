<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_mesin',
        'nama_mesin',
        'nama_produk',
        'part_no',
        'cycle_time',
        'cavity',
    ];
}
