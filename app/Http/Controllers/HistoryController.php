<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil nilai 'periode' dari query parameter, default ke 1 jika tidak ada
        $periode = request()->query('periode') ? request()->query('periode') : 1;

        // Query data dari tabel 'lamps' dan 'histories'
        $lampsData = DB::table('lamps as a')
            ->join('histories as b', 'a.id', '=', 'b.lamp_id')
            ->select(
                DB::raw('COUNT(b.lamp_id) as total'),
                'a.name',
                'b.status',
                DB::raw('DATE(b.created_at) as tanggal')
            )
            ->whereBetween('b.created_at', [now()->subDays($periode), now()])
            ->groupBy('tanggal', 'a.name', 'b.lamp_id')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Inisialisasi array untuk chart
        $chartData = [
            'labels' => [],
            'total' => []
        ];

        // Looping data hasil query untuk mengisi chartData
        foreach ($lampsData as $data) {
            $chartData['labels'][] = $data->name . ' - ' . $data->tanggal;
            $chartData['total'][] = $data->total;
        }

        // Kirim data ke view
        return view('histories.index', compact('chartData', 'periode'));
    }
}
