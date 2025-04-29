<?php

// Di file Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_mesin',
        'nama_mesin',
        'nama_produk',
        'part_no',
        'cycle_time',
        'cavity',
    ];

    // Tambahkan relasi ke ProductLog
    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }
}