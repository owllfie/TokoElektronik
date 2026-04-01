<?php

namespace App\Http\Controllers;

use App\Exports\StockTableExport;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $reportQuery = $this->reportQuery($startDate, $endDate);
        $summary = $this->summaryQuery($startDate, $endDate)->first();

        $stocks = $reportQuery
            ->orderByDesc('stock.created_at')
            ->paginate(10)
            ->withQueryString();

        return view('report', [
            'stocks' => $stocks,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $summary,
        ]);
    }

    public function print(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        return view('exports.stock-print', [
            'title' => 'Stock Report',
            'stocks' => $this->reportQuery($startDate, $endDate)->orderByDesc('stock.created_at')->get(),
            'meta' => $this->reportMeta($startDate, $endDate),
        ]);
    }

    public function pdf(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        $pdf = Pdf::loadView('exports.stock-pdf', [
            'title' => 'Stock Report',
            'stocks' => $this->reportQuery($startDate, $endDate)->orderByDesc('stock.created_at')->get(),
            'meta' => $this->reportMeta($startDate, $endDate),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('stock-report-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    public function excel(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        return Excel::download(
            new StockTableExport(
                'Stock Report',
                $this->reportQuery($startDate, $endDate)->orderByDesc('stock.created_at')->get(),
                $this->reportMeta($startDate, $endDate),
            ),
            'stock-report-' . $startDate . '-to-' . $endDate . '.xlsx'
        );
    }

    private function resolveDateRange(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        return [
            $validated['start_date'] ?? now()->subDays(30)->toDateString(),
            $validated['end_date'] ?? now()->toDateString(),
        ];
    }

    private function reportQuery(string $startDate, string $endDate)
    {
        return Stock::query()
            ->leftJoin('barang', 'stock.id_barang', '=', 'barang.id_barang')
            ->select('stock.*', 'barang.nama_barang')
            ->whereDate('stock.created_at', '>=', $startDate)
            ->whereDate('stock.created_at', '<=', $endDate);
    }

    private function summaryQuery(string $startDate, string $endDate)
    {
        return Stock::query()
            ->whereDate('stock.created_at', '>=', $startDate)
            ->whereDate('stock.created_at', '<=', $endDate)
            ->selectRaw('COUNT(*) as total_records, COALESCE(SUM(stock.jumlah), 0) as total_jumlah, COALESCE(SUM(stock.total_harga), 0) as total_nilai');
    }

    private function reportMeta(string $startDate, string $endDate): array
    {
        return [
            'Start Date' => $startDate,
            'End Date' => $endDate,
        ];
    }
}
