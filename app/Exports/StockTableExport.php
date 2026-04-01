<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class StockTableExport implements FromView
{
    public function __construct(
        protected string $title,
        protected Collection $stocks,
        protected array $meta = [],
    ) {
    }

    public function view(): View
    {
        return view('exports.stock-table', [
            'title' => $this->title,
            'stocks' => $this->stocks,
            'meta' => $this->meta,
        ]);
    }
}
