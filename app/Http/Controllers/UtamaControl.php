<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaControl extends Controller
{
    public function index()
    {
        $grafikData = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->selectRaw('DATE(created_at) as tanggal, 
                         ROUND(AVG(availability), 2) as availability, 
                         ROUND(AVG(performance), 2) as performance, 
                         ROUND(AVG(oee), 2) as oee')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();
    
        return view('halaman_utama.index', compact('grafikData'));
    }
    
}
