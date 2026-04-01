<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Toko Elektronik')</title>
    <style>
        .layout.collapsed {
            grid-template-columns: 80px 1fr !important;
        }

        .layout.collapsed aside {
            padding: 24px 12px !important;
        }

        .toggle {
            border: none;
            background: rgba(15, 107, 92, 0.12);
            color: #1b1b1b;
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

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand a {
            color: inherit;
            text-decoration: none;
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
            box-shadow: 0 12px 30px rgba(15, 34, 29, 0.12);
        }

        .side-nav {
            display: grid;
            gap: 12px;
            font-size: 15px;
        }

        .side-nav a,
        .side-nav button {
            text-decoration: none;
            color: #1b1b1b;
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(15, 107, 92, 0.18);
            font-family: inherit;
            cursor: pointer;
            text-align: left;
        }

        .side-nav a:hover,
        .side-nav button:hover {
            background: rgba(15, 107, 92, 0.08);
        }

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
        $brandName = $sharedCompanyName ?? (trim($__env->yieldContent('brand_name')) ?: 'Electro');
        $brandMark = $sharedCompanyMark ?? (trim($__env->yieldContent('brand_mark')) ?: 'E');
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
