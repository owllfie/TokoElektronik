@php
    $user = session('user');
    $role = (int) ($user['role'] ?? 0);
    $accessiblePageKeys = $accessiblePageKeys ?? [];
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
            @if(in_array('users', $accessiblePageKeys, true))
                <a href="{{ route('users') }}"><span>Users</span></a>
            @endif
            @if(in_array('items', $accessiblePageKeys, true))
                <a href="{{ route('items') }}"><span>Items</span></a>
            @endif
            @if(in_array('types', $accessiblePageKeys, true))
                <a href="{{ route('types') }}"><span>Item Types</span></a>
            @endif
            @if(in_array('stock', $accessiblePageKeys, true))
                <a href="{{ route('stock') }}"><span>Stock</span></a>
            @endif
            @if(in_array('retur', $accessiblePageKeys, true))
                <a href="{{ route('retur') }}"><span>Retur</span></a>
            @endif
            @if(in_array('report', $accessiblePageKeys, true))
                <a href="{{ route('report') }}"><span>Report</span></a>
            @endif
            @if($role === 4)
                <a href="{{ route('admin.settings') }}"><span>Web Settings</span></a>
                <a href="{{ route('admin.access') }}"><span>Manage Access</span></a>
                <a href="{{ route('admin.backup') }}"><span>Backup Database</span></a>
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
