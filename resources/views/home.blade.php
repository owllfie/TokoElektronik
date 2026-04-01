@extends('layouts.app')

@section('title', 'Toko Elektronik | Home')
@section('brand_name', 'Electro')
@section('brand_mark', 'E')

@section('styles')
:root {
--ink: #1b1b1b;
--muted: #6b6b6b;
--accent: #0f6b5c;
--accent-2: #f0b429;
--surface: #fff7ee;
--shadow: rgba(15, 34, 29, 0.12);
}

* {
box-sizing: border-box;
}

body {
margin: 0;
font-family: "Calibri", "Segoe UI", sans-serif;
color: var(--ink);
background:
radial-gradient(circle at 15% 20%, rgba(240, 180, 41, 0.2), transparent 50%),
radial-gradient(circle at 85% 10%, rgba(15, 107, 92, 0.18), transparent 55%),
linear-gradient(120deg, #fef4e8 0%, #f6f1ff 40%, #f0f9f6 100%);
min-height: 100vh;
}

.layout {
display: grid;
grid-template-columns: 240px 1fr;
min-height: 100vh;
transition: grid-template-columns 0.2s ease;
}

aside {
padding: 32px 20px;
background: rgba(255, 255, 255, 0.6);
border-right: 1px solid rgba(15, 107, 92, 0.12);
display: flex;
flex-direction: column;
gap: 24px;
backdrop-filter: blur(8px);
}

.layout.collapsed {
grid-template-columns: 80px 1fr;
}

.layout.collapsed aside {
padding: 24px 12px;
}

.toggle {
border: none;
background: rgba(15, 107, 92, 0.12);
color: var(--ink);
border-radius: 10px;
padding: 8px 10px;
font-size: 14px;
font-weight: 700;
cursor: pointer;
text-align: left;
}

.layout.collapsed .brand,
.layout.collapsed .side-nav a,
.layout.collapsed .side-nav button {
justify-content: center;
text-align: center;
}

.layout.collapsed .brand div,
.layout.collapsed .side-nav span,
.layout.collapsed small {
display: none;
}

.side-nav {
display: grid;
gap: 12px;
font-size: 15px;
}

.side-nav a,
.side-nav button {
text-decoration: none;
color: var(--ink);
padding: 10px 14px;
border-radius: 12px;
background: rgba(255, 255, 255, 0.7);
border: 1px solid rgba(15, 107, 92, 0.18);
font-family: inherit;
cursor: pointer;
text-align: left;
}

header {
padding: 32px 6vw 16px;
display: flex;
align-items: center;
justify-content: space-between;
gap: 24px;
}

.brand {
display: flex;
align-items: center;
gap: 14px;
font-family: "Georgia", "Times New Roman", serif;
font-weight: 700;
letter-spacing: 1px;
}

.brand-mark {
width: 46px;
height: 46px;
border-radius: 14px;
background: linear-gradient(135deg, #0f6b5c, #0d3d36);
display: grid;
place-items: center;
color: #fff;
font-size: 20px;
box-shadow: 0 12px 30px var(--shadow);
}

nav {
display: flex;
gap: 16px;
align-items: center;
font-size: 15px;
}

nav a,
.nav-button {
text-decoration: none;
color: var(--ink);
padding: 10px 16px;
border-radius: 999px;
background: rgba(255, 255, 255, 0.6);
border: 1px solid rgba(15, 107, 92, 0.2);
transition: transform 0.2s ease, box-shadow 0.2s ease;
font-family: inherit;
font-size: 15px;
cursor: pointer;
}

nav a:hover,
.nav-button:hover {
transform: translateY(-2px);
box-shadow: 0 10px 20px var(--shadow);
}

.hero {
padding: 24px 6vw 48px;
display: grid;
grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
gap: 28px;
align-items: center;
}

.hero-card {
background: rgba(255, 255, 255, 0.78);
border-radius: 28px;
padding: 32px;
box-shadow: 0 28px 60px var(--shadow);
border: 1px solid rgba(15, 107, 92, 0.12);
backdrop-filter: blur(8px);
}

.hero h1 {
margin: 0 0 12px;
font-size: clamp(2rem, 3vw, 3rem);
line-height: 1.1;
font-family: "Georgia", "Times New Roman", serif;
}

.hero p {
margin: 0 0 20px;
color: var(--muted);
font-size: 1.05rem;
line-height: 1.6;
}

.cta-row {
display: flex;
flex-wrap: wrap;
gap: 12px;
}

.cta {
padding: 12px 18px;
border-radius: 12px;
border: none;
font-weight: 600;
cursor: pointer;
background: var(--accent);
color: #fff;
box-shadow: 0 12px 24px rgba(15, 107, 92, 0.25);
}

.cta.secondary {
background: transparent;
color: var(--accent);
border: 1px solid var(--accent);
box-shadow: none;
}

.signal-grid {
display: grid;
grid-template-columns: repeat(2, minmax(0, 1fr));
gap: 16px;
}

.signal {
background: #fff;
border-radius: 18px;
padding: 18px;
border: 1px solid rgba(0, 0, 0, 0.05);
min-height: 120px;
display: flex;
flex-direction: column;
justify-content: space-between;
}

.signal h3 {
margin: 0 0 6px;
font-size: 16px;
}

.signal span {
font-size: 26px;
font-weight: 700;
}

.banner {
padding: 18px 24px;
border-radius: 16px;
margin-top: 22px;
background: linear-gradient(135deg, rgba(15, 107, 92, 0.2), rgba(240, 180, 41, 0.15));
display: flex;
align-items: center;
justify-content: space-between;
gap: 16px;
}

.banner p {
margin: 0;
color: var(--ink);
font-weight: 600;
}

.panel {
background: #fff;
border-radius: 20px;
padding: 24px;
box-shadow: 0 20px 40px var(--shadow);
}

.panel h2 {
margin: 0 0 16px;
font-size: 20px;
}

.chart-card {
margin-top: 20px;
padding: 18px;
border-radius: 18px;
background: #f4f4f4;
border: 1px solid rgba(0, 0, 0, 0.06);
}

.chart-header {
display: flex;
align-items: center;
justify-content: space-between;
gap: 12px;
margin-bottom: 12px;
}

.chart-line {
width: 100%;
height: 220px;
display: block;
}

.list {
display: grid;
gap: 12px;
}

.list-item {
display: flex;
align-items: center;
justify-content: space-between;
padding: 12px 16px;
border-radius: 14px;
background: #f7f7f7;
}

.status {
background: var(--accent-2);
color: #332300;
font-weight: 700;
padding: 4px 10px;
border-radius: 999px;
font-size: 12px;
}

@media (max-width: 900px) {
.layout {
grid-template-columns: 1fr;
}

aside {
flex-direction: row;
align-items: center;
justify-content: space-between;
}

.side-nav {
grid-auto-flow: column;
grid-template-columns: unset;
}
}

@media (max-width: 700px) {
header {
flex-direction: column;
align-items: flex-start;
}

.signal-grid {
grid-template-columns: 1fr;
}

.banner {
flex-direction: column;
align-items: flex-start;
}
}
@endsection

@section('content')
@if ($simpleWelcome ?? false)
<main class="hero">
    <section class="panel">
        <h2>Welcome</h2>
        <p>Welcome, {{ $username }}.</p>
    </section>
</main>
@else
@php
$chartWidth = 700;
$chartHeight = 220;
$leftPad = 20;
$rightPad = 20;
$topPad = 30;
$bottomPad = 30;
$usableWidth = $chartWidth - $leftPad - $rightPad;
$usableHeight = $chartHeight - $topPad - $bottomPad;
$pointCount = max($chartDays->count() - 1, 1);

$chartPoints = $chartDays->map(function ($day, $index) use ($leftPad, $topPad, $usableWidth, $usableHeight, $pointCount, $maxChartValue) {
$x = $leftPad + (($usableWidth / $pointCount) * $index);
$y = $topPad + $usableHeight - (($day['value'] / $maxChartValue) * $usableHeight);

return [
'x' => round($x, 2),
'y' => round($y, 2),
'label' => $day['label'],
'value' => $day['value'],
];
});

$linePath = $chartPoints->map(fn ($point, $index) => ($index === 0 ? 'M' : 'L') . $point['x'] . ' ' . $point['y'])->implode(' ');
$areaPath = $linePath . ' L' . ($chartWidth - $rightPad) . ' ' . ($chartHeight - $bottomPad) . ' L' . $leftPad . ' ' . ($chartHeight - $bottomPad) . ' Z';
$gridLines = collect(range(0, 3))->map(function ($step) use ($leftPad, $rightPad, $topPad, $usableHeight) {
return round($topPad + (($usableHeight / 3) * $step), 2);
});
@endphp
<main class="hero">
    <section class="panel">
        <h2>Statistics</h2>
        <div class="signal-grid">
            <div class="signal">
                <h3>Total Records</h3>
                <span>{{ number_format($summary->total_records ?? 0) }}</span>
                <small>Report range: {{ \Illuminate\Support\Carbon::parse($startDate)->format('d M Y') }} - {{ \Illuminate\Support\Carbon::parse($endDate)->format('d M Y') }}</small>
            </div>
            <div class="signal">
                <h3>Total Quantity</h3>
                <span>{{ number_format($summary->total_jumlah ?? 0) }}</span>
                <small>Combined stock movement in report range</small>
            </div>
            <div class="signal">
                <h3>Total Value</h3>
                <span>Rp {{ number_format($summary->total_nilai ?? 0, 0, ',', '.') }}</span>
                <small>Sum of stock transaction value</small>
            </div>
            <div class="signal">
                <h3>Top Item</h3>
                <span>{{ $topItem->nama_barang ?? '-' }}</span>
                <small>{{ $topItem ? 'Rp ' . number_format($topItem->total_nilai, 0, ',', '.') : 'No stock data in range' }}</small>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-header">
                <strong>Last 7 Days Value</strong>
                <span class="status">{{ number_format($stockInCount) }} IN / {{ number_format($stockOutCount) }} OUT</span>
            </div>
            <svg class="chart-line" viewBox="0 0 700 220" role="img" aria-label="Last 7 days stock value chart">
                <defs>
                    <linearGradient id="lineFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#0f6b5c" stop-opacity="0.35" />
                        <stop offset="100%" stop-color="#0f6b5c" stop-opacity="0.05" />
                    </linearGradient>
                </defs>
                <rect x="0" y="0" width="700" height="220" fill="none" />
                <g stroke="rgba(15, 107, 92, 0.12)" stroke-width="1">
                    @foreach ($gridLines as $lineY)
                    <line x1="20" y1="{{ $lineY }}" x2="680" y2="{{ $lineY }}" />
                    @endforeach
                </g>
                <path d="{{ $linePath }}"
                    fill="none"
                    stroke="#0f6b5c"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="{{ $areaPath }}"
                    fill="url(#lineFill)" />
                <g fill="#0f6b5c">
                    @foreach ($chartPoints as $point)
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" />
                    @endforeach
                </g>
                <g fill="#6b6b6b" font-size="12">
                    @foreach ($chartPoints as $point)
                    <text x="{{ $point['x'] }}" y="210" text-anchor="{{ $loop->first ? 'start' : ($loop->last ? 'end' : 'middle') }}">{{ $point['label'] }}</text>
                    @endforeach
                </g>
            </svg>
        </div>
    </section>
</main>
@endif
@endsection