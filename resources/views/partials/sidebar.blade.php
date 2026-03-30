@php($user = session('user'))
<aside>
    <div class="brand">
        @if (!empty($brandMark))
            <div class="brand-mark">{{ $brandMark }}</div>
        @endif
        <div><a href="/home">{{ $brandName ?? 'Toko Elektronik' }}</a></div>
    </div>
    <nav class="side-nav">
        <button class="toggle" type="button" id="toggleSidebar">Menu</button>
        <a href="/users"><span>Users</span></a>
        <a href="/items"><span>Items</span></a>
        <a href="/types"><span>Item Types</span></a>
        <a href="/report"><span>Report</span></a>
        <a href="/stock"><span>Stock</span></a>
        @if($user)
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="/login">Login</a>
        @endif
    </nav>
</aside>
