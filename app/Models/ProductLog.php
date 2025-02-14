<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    protected $fillable = [
        'product_id',
        'no_mesin', // Tambahkan ini
        'action',
        'user',
        'detail', // ✅ Tambahkan ini agar bisa menyimpan perubahan detail!
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
