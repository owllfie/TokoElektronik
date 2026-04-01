<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Toko Elektronik')</title>
    <style>
        .pagination-wrap {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding-top: 8px;
        }

        .pagination-summary {
            margin: 0;
            color: #6b6b6b;
            font-size: 14px;
        }

        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid rgba(15, 107, 92, 0.18);
            background: #fff;
            color: #1b1b1b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(15, 34, 29, 0.08);
        }

        .pagination a:hover {
            background: rgba(15, 107, 92, 0.08);
            border-color: rgba(15, 107, 92, 0.28);
        }

        .pagination .active {
            background: linear-gradient(135deg, #0f6b5c, #0d3d36);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 14px 24px rgba(15, 107, 92, 0.22);
        }

        .pagination .disabled {
            color: #a0a0a0;
            background: rgba(255, 255, 255, 0.7);
            box-shadow: none;
            cursor: not-allowed;
        }

        @yield('styles')
    </style>
</head>
<body>
    @php
        $brandName = trim($__env->yieldContent('brand_name')) ?: 'Electro';
        $brandMark = trim($__env->yieldContent('brand_mark')) ?: 'E';
    @endphp
    <div class="layout">
        @include('partials.sidebar', ['brandName' => $brandName, 'brandMark' => $brandMark])
        @yield('content')
    </div>
    <script>
        const layout = document.querySelector('.layout');
        const toggle = document.getElementById('toggleSidebar');
        if (layout && toggle) {
            toggle.addEventListener('click', () => {
                layout.classList.toggle('collapsed');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
