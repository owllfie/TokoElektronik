@php
    $user = session('user');
    $role = (int) ($user['role'] ?? 0);
    $canAccessInventory = in_array($role, [1, 3, 4], true);
    $canAccessReport = in_array($role, [2, 3, 4], true);
    $canAccessUsers = in_array($role, [3, 4], true);
    $homeRoute = match ($role) {
        1, 2, 3, 4 => route('home'),
        default => route('landing'),
    };
@endphp
<aside>
    <div class="brand">
        @if (!empty($brandMark))
            <div class="brand-mark">{{ $brandMark }}</div>
        @endif
        <div><a href="{{ $homeRoute }}">{{ $brandName ?? 'Toko Elektronik' }}</a></div>
    </div>
    <nav class="side-nav">
        @if($user)
            <button class="toggle" type="button" id="toggleSidebar">Menu</button>
            <a href="{{ route('profile') }}"><span>Profile</span></a>
            @if($canAccessUsers)
                <a href="{{ route('users') }}"><span>Users</span></a>
            @endif
            @if($canAccessInventory)
                <a href="{{ route('items') }}"><span>Items</span></a>
                <a href="{{ route('types') }}"><span>Item Types</span></a>
                <a href="{{ route('stock') }}"><span>Stock</span></a>
            @endif
            @if($canAccessReport)
                <a href="{{ route('report') }}"><span>Report</span></a>
            @endif
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endif
    </nav>
</aside>
