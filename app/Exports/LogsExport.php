<?php

namespace App\Exports;

use App\Models\ProductLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ProductLog::with(['product' => function($query) {
            $query->withTrashed();
        }])->orderBy('created_at', 'desc')->get([
            'id',
            'no_mesin',
            'action',
            'user',
            'created_at',
            'detail'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID Log',
            'Nomor Mesin',
            'Aksi',
            'Pengguna',
            'Waktu',
            'Detail Perubahan'
        ];
    }
}