<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        $stockSummaries = [
            'in' => $this->buildStockSummary(clone $baseQuery, 'in'),
            'out' => $this->buildStockSummary(clone $baseQuery, 'out'),
        ];

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

        $chartTabs = [
            'daily' => $this->buildDailyChartTab($endDate),
            'monthly' => $this->buildMonthlyChartTab($endDate),
        ];

        return view('home', [
            'simpleWelcome' => false,
            'username' => $user['username'] ?? 'User',
            'summary' => $summary,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'stockInCount' => $stockInCount,
            'stockOutCount' => $stockOutCount,
            'stockSummaries' => $stockSummaries,
            'topItem' => $topItem,
            'latestStocks' => $latestStocks,
            'chartTabs' => $chartTabs,
        ]);
    }

    private function buildStockSummary($query, string $type): array
    {
        $summary = (clone $query)
            ->where('stock.tipe', $type)
            ->selectRaw('COUNT(*) as total_records, COALESCE(SUM(stock.jumlah), 0) as total_jumlah, COALESCE(SUM(stock.total_harga), 0) as total_nilai')
            ->first();

        $topItem = (clone $query)
            ->where('stock.tipe', $type)
            ->selectRaw('barang.nama_barang, COALESCE(SUM(stock.total_harga), 0) as total_nilai')
            ->whereNotNull('barang.nama_barang')
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_nilai')
            ->first();

        return [
            'total_records' => (int) ($summary->total_records ?? 0),
            'total_jumlah' => (float) ($summary->total_jumlah ?? 0),
            'total_nilai' => (float) ($summary->total_nilai ?? 0),
            'top_item_name' => $topItem->nama_barang ?? '-',
            'top_item_value' => (float) ($topItem->total_nilai ?? 0),
        ];
    }

    private function buildDailyChartTab(string $endDate): array
    {
        $end = Carbon::parse($endDate);
        $start = $end->copy()->subDays(6)->toDateString();

        $dailyRows = Stock::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as chart_key, tipe, COALESCE(SUM(jumlah), 0) as total_jumlah')
            ->groupBy('chart_key', 'tipe')
            ->orderBy('chart_key')
            ->get();

        $series = collect(CarbonPeriod::create($start, $endDate))
            ->map(function ($date) use ($dailyRows) {
                $dateString = $date->toDateString();

                return [
                    'key' => $dateString,
                    'label' => $date->format('D'),
                    'stock_in' => $this->extractTotal($dailyRows, $dateString, 'in'),
                    'stock_out' => $this->extractTotal($dailyRows, $dateString, 'out'),
                ];
            })
            ->values();

        return [
            'label' => 'Daily',
            'description' => 'Last 7 days stock movement',
            'series' => $series,
        ];
    }

    private function buildMonthlyChartTab(string $endDate): array
    {
        $end = Carbon::parse($endDate)->startOfMonth();
        $start = $end->copy()->subMonths(5)->startOfMonth();

        $monthlyRows = Stock::query()
            ->whereDate('created_at', '>=', $start->toDateString())
            ->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as chart_key, tipe, COALESCE(SUM(jumlah), 0) as total_jumlah")
            ->groupBy('chart_key', 'tipe')
            ->orderBy('chart_key')
            ->get();

        $months = collect(range(0, 5))
            ->map(fn (int $index) => $start->copy()->addMonths($index));

        $series = $months
            ->map(function (Carbon $month) use ($monthlyRows) {
                $monthKey = $month->format('Y-m');

                return [
                    'key' => $monthKey,
                    'label' => $month->format('M'),
                    'stock_in' => $this->extractTotal($monthlyRows, $monthKey, 'in'),
                    'stock_out' => $this->extractTotal($monthlyRows, $monthKey, 'out'),
                ];
            })
            ->values();

        return [
            'label' => 'Monthly',
            'description' => 'Last 6 months stock movement',
            'series' => $series,
        ];
    }

    private function extractTotal(Collection $rows, string $chartKey, string $type): float
    {
        return (float) optional(
            $rows->first(fn ($row) => $row->chart_key === $chartKey && $row->tipe === $type)
        )->total_jumlah;
    }
}
