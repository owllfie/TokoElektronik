<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Toko Elektronik')</title>
    <style>
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
