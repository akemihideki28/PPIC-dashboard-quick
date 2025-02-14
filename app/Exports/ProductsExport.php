<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all(['id', 'no_mesin', 'nama_mesin', 'nama_produk', 'part_no', 'cycle_time', 'cavity', 'created_at']);
    }

    public function headings(): array
    {
        return [
            'No',
            'no_mesin',
            'Mesin',
            'Nama Produk',
            'Part No',
            'Cycle Time',
            'Cavity',
            'Added Time',
        ];
    }
}
