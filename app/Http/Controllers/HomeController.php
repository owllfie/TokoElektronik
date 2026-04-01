<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user', []);
        $role = (int) ($user['role'] ?? 0);

        if (in_array($role, [1, 2], true)) {
            return view('home', [
                'simpleWelcome' => true,
                'username' => $user['username'] ?? 'User',
            ]);
        }

        $startDate = now()->subDays(30)->toDateString();
        $endDate = now()->toDateString();

        $baseQuery = Stock::query()
            ->leftJoin('barang', 'stock.id_barang', '=', 'barang.id_barang')
            ->whereDate('stock.created_at', '>=', $startDate)
            ->whereDate('stock.created_at', '<=', $endDate);

        $summary = Stock::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('COUNT(*) as total_records, COALESCE(SUM(jumlah), 0) as total_jumlah, COALESCE(SUM(total_harga), 0) as total_nilai')
            ->first();

        $stockInCount = (clone $baseQuery)->where('stock.tipe', 'in')->count();
        $stockOutCount = (clone $baseQuery)->where('stock.tipe', 'out')->count();

        $topItem = (clone $baseQuery)
            ->selectRaw('barang.nama_barang, COALESCE(SUM(stock.total_harga), 0) as total_nilai')
            ->whereNotNull('barang.nama_barang')
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_nilai')
            ->first();

        $latestStocks = (clone $baseQuery)
            ->select('stock.*', 'barang.nama_barang')
            ->orderByDesc('stock.created_at')
            ->limit(2)
            ->get();

        $chartStart = now()->subDays(6)->toDateString();
        $dailyRows = Stock::query()
            ->whereDate('created_at', '>=', $chartStart)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as chart_date, COALESCE(SUM(total_harga), 0) as total_nilai')
            ->groupBy('chart_date')
            ->orderBy('chart_date')
            ->get()
            ->keyBy('chart_date');

        $chartDays = collect(CarbonPeriod::create($chartStart, $endDate))
            ->map(function ($date) use ($dailyRows) {
                $dateString = $date->toDateString();

                return [
                    'label' => $date->format('D'),
                    'date' => $dateString,
                    'value' => (float) optional($dailyRows->get($dateString))->total_nilai,
                ];
            })
            ->values();

        $maxChartValue = max($chartDays->max('value'), 1);

        return view('home', [
            'simpleWelcome' => false,
            'username' => $user['username'] ?? 'User',
            'summary' => $summary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'stockInCount' => $stockInCount,
            'stockOutCount' => $stockOutCount,
            'topItem' => $topItem,
            'latestStocks' => $latestStocks,
            'chartDays' => $chartDays,
            'maxChartValue' => $maxChartValue,
        ]);
    }
}
