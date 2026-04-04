<nav class="navbar">
    <div>
        <h1>🎫 IT Help Desk</h1>
    </div>
    <div class="navbar-links">
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('tickets.admin-index') }}">Visas biļetes</a>
                <a href="{{ route('tickets.calendar') }}">Kalendārs</a>
            @else
                <a href="{{ route('tickets.index') }}">Manas biļetes</a>
                <a href="{{ route('tickets.create') }}">Jauna biļete</a>
            @endif
            <span style="color: white;">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; text-decoration: underline;">Izlogoties</button>
            </form>
        @else
            <a href="{{ route('login') }}">Pieslēgties</a>
            <a href="{{ route('register') }}">Reģistrēties</a>
        @endauth
    </div>
</nav>