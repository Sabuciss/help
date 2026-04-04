<nav class="navbar">
    <div class="navbar-container">
        <!-- Hamburger -->
        <button class="hamburger" onclick="toggleMenu()">
            ☰
        </button>

        <a href="{{ route('home') }}" class="home-link">🎫 IT Help Desk</a>
    </div>

    <div class="navbar-menu" id="navbarMenu">
        @auth
            @if(Auth::user()->isAdmin())
                <div class="navbar-links">
                    <a href="{{ route('tickets.admin-index') }}" class="{{ request()->routeIs('tickets.admin-index') ? 'active' : '' }}">
                        Visas biļetes
                    </a>

                    <a href="{{ route('tickets.calendar') }}" class="{{ request()->routeIs('tickets.calendar') ? 'active' : '' }}">
                        Kalendārs
                    </a>
                </div>
            @else
                <div class="navbar-links">
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                        Manas biļetes
                    </a>

                    <a href="{{ route('tickets.create') }}" class="{{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        Jauna biļete
                    </a>
                </div>
            @endif

            <div class="navbar-end">
                <span>Welcome, {{ Auth::user()->name }}!</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Izlogoties</button>
                </form>
            </div>
        @else
            <div class="navbar-actions">
                <a href="{{ route('login') }}" class="btn-login">Pieslēgties</a>
                <a href="{{ route('register') }}" class="btn-register">Reģistrēties</a>
            </div>
        @endauth
    </div>
</nav>

<!-- JS -->
<script>
    function toggleMenu() {
        document.getElementById('navbarMenu').classList.toggle('active');
    }
</script>