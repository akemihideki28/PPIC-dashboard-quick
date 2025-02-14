<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtamaControl extends Controller
{
    public function index()
    {
        // Contoh data penjualan bulanan
        $salesData = [
            'Januari' => 50,
            'Februari' => 60,
            'Maret' => 70,
            'April' => 80,
            'Mei' => 90,
            'Juni' => 100,
            'Juli' => 110,
            'Agustus' => 120,
            'September' => 130,
            'Oktober' => 140,
            'November' => 150,
            'Desember' => 160,
        ];

        return view('halaman_utama.index', compact('salesData'));
    }
    public function updateSalesData(Request $request)
{
    // Validasi input dari formulir
    $validated = $request->validate([
        'salesData' => 'required|array', // Data harus berupa array
        'salesData.*' => 'required|numeric|min:0', // Setiap nilai dalam array harus angka positif
    ]);

    // Ambil data yang telah divalidasi
    $salesData = $validated['salesData'];

    // Kirim data kembali ke view untuk diperbarui
    return view('halaman_utama.index', compact('salesData'));
}

}
