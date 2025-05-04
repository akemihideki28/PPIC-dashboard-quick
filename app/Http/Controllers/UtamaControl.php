<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtamaControl extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', 'harian');
        $shift = $request->input('shift');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $machine = $request->input('machine');

        // Query untuk data trend
        $trendQuery = DB::connection('laragon')
            ->table('hasil_end_shift');

        // Query untuk data pareto (dikelompokkan berdasarkan mesin)
        // Changed to use no_mesin instead of nama_mesin
        $paretoQuery = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->select('no_mesin') // Changed from nama_mesin to no_mesin
            ->selectRaw('AVG(oee) as avg_oee')
            ->selectRaw('AVG(availability) as avg_availability')
            ->selectRaw('AVG(performance) as avg_performance')
            ->selectRaw('AVG(ok_ratio) as avg_quality')
            ->groupBy('no_mesin'); // Changed from nama_mesin to no_mesin

        // Query for downtime reasons data
        $downtimeQuery = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->select('alasan')
            ->selectRaw('SUM(lost_time) as total_lost_time')
            ->selectRaw('COUNT(*) as occurrence_count')
            ->groupBy('alasan');

        if ($shift) {
            $trendQuery->where('shift', $shift);
            $paretoQuery->where('shift', $shift);
            $downtimeQuery->where('shift', $shift);
        }

        if ($machine) {
            $trendQuery->where('no_mesin', $machine); // Changed from nama_mesin to no_mesin
            $paretoQuery->where('no_mesin', $machine); // Changed from nama_mesin to no_mesin
            $downtimeQuery->where('no_mesin', $machine); // Changed from nama_mesin to no_mesin
        }

        // Apply date range filter for custom range
        if ($range === 'custom' && $startDate && $endDate) {
            $trendQuery->whereBetween('created_at', [$startDate, $endDate]);
            $paretoQuery->whereBetween('created_at', [$startDate, $endDate]);
            $downtimeQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Grouping untuk trend data
        switch ($range) {
            case 'mingguan':
                $trendQuery->selectRaw('YEARWEEK(created_at, 1) as waktu,
                    ROUND(AVG(availability), 2) as availability,
                    ROUND(AVG(performance), 2) as performance,
                    ROUND(AVG(ok_ratio), 2) as ok_ratio,
                    ROUND(AVG(oee), 2) as oee,
                    GROUP_CONCAT(DISTINCT alasan ORDER BY alasan ASC) as alasan_list,
                    SUM(lost_time) as total_lost_time');
                $trendQuery->groupByRaw('YEARWEEK(created_at, 1)');
                break;

            case 'bulanan':
                $trendQuery->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as waktu,
                    ROUND(AVG(availability), 2) as availability,
                    ROUND(AVG(performance), 2) as performance,
                    ROUND(AVG(ok_ratio), 2) as ok_ratio,
                    ROUND(AVG(oee), 2) as oee,
                    GROUP_CONCAT(DISTINCT alasan ORDER BY alasan ASC) as alasan_list,
                    SUM(lost_time) as total_lost_time');
                $trendQuery->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")');
                break;

            case 'tahunan':
                $trendQuery->selectRaw('YEAR(created_at) as waktu,
                    ROUND(AVG(availability), 2) as availability,
                    ROUND(AVG(performance), 2) as performance,
                    ROUND(AVG(ok_ratio), 2) as ok_ratio,
                    ROUND(AVG(oee), 2) as oee,
                    GROUP_CONCAT(DISTINCT alasan ORDER BY alasan ASC) as alasan_list,
                    SUM(lost_time) as total_lost_time');
                $trendQuery->groupByRaw('YEAR(created_at)');
                break;

            case 'custom':
                $trendQuery->selectRaw('DATE(created_at) as waktu,
                    ROUND(AVG(availability), 2) as availability,
                    ROUND(AVG(performance), 2) as performance,
                    ROUND(AVG(ok_ratio), 2) as ok_ratio,
                    ROUND(AVG(oee), 2) as oee,
                    GROUP_CONCAT(DISTINCT alasan ORDER BY alasan ASC) as alasan_list,
                    SUM(lost_time) as total_lost_time');
                $trendQuery->groupByRaw('DATE(created_at)');
                break;

            default: // harian
                $trendQuery->selectRaw('DATE(created_at) as waktu,
                    ROUND(AVG(availability), 2) as availability,
                    ROUND(AVG(performance), 2) as performance,
                    ROUND(AVG(ok_ratio), 2) as ok_ratio,
                    ROUND(AVG(oee), 2) as oee,
                    GROUP_CONCAT(DISTINCT alasan ORDER BY alasan ASC) as alasan_list,
                    SUM(lost_time) as total_lost_time');
                $trendQuery->groupByRaw('DATE(created_at)');
                break;
        }

        $grafikData = $trendQuery->orderBy('waktu')->get();
        $paretoData = $paretoQuery->orderByDesc('avg_oee')->get();
        $downtimeData = $downtimeQuery->orderByDesc('total_lost_time')->get();
        
        // Get detailed downtime information
        $lostTimeDetails = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->select('id', 'alasan', 'lost_time', 'lost_time_details', 'created_at', 'no_mesin as mesin', 'shift'); // Changed nama_mesin to no_mesin with alias
            
        if ($shift) {
            $lostTimeDetails->where('shift', $shift);
        }
        
        if ($machine) {
            $lostTimeDetails->where('no_mesin', $machine); // Changed from nama_mesin to no_mesin
        }
        
        if ($range === 'custom' && $startDate && $endDate) {
            $lostTimeDetails->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $lostTimeDetails = $lostTimeDetails->orderBy('created_at', 'desc')->get();
        
        // Process JSON data in lost_time_details
        foreach ($lostTimeDetails as $detail) {
            if (!empty($detail->lost_time_details)) {
                $detail->parsed_details = json_decode($detail->lost_time_details);
            } else {
                $detail->parsed_details = [];
            }
        }
        
        // Dapatkan daftar mesin untuk dropdown filter
        $machines = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->select('no_mesin') // Changed from nama_mesin to no_mesin
            ->distinct()
            ->orderBy('no_mesin') // Changed from nama_mesin to no_mesin
            ->pluck('no_mesin'); // Changed from nama_mesin to no_mesin
            
        $lastUpdate = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->orderBy('created_at', 'desc')
            ->value('created_at');

        return view('halaman_utama.index', compact(
            'grafikData', 
            'paretoData',
            'range', 
            'shift', 
            'request',
            'machines',
            'machine',
            'lastUpdate',
            'downtimeData',
            'lostTimeDetails'
        ));
    }
    
    public function checkUpdates(Request $request)
    {
        $lastKnownUpdate = $request->input('last_update');
        
        $newLastUpdate = DB::connection('laragon')
            ->table('hasil_end_shift')
            ->orderBy('created_at', 'desc')
            ->value('created_at');
        
        return response()->json([
            'has_update' => $newLastUpdate != $lastKnownUpdate,
            'new_last_update' => $newLastUpdate
        ]);
    }
}