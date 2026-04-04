@extends('layouts.app')

@section('title', 'IT Help Desk - Mājas lapa')

@section('content')

<!-- HERO -->
<div class="hero">
    <h1>🎫 IT Help Desk</h1>
    <p>Ātrs, vienkāršs un gudrs veids, kā pārvaldīt IT problēmas bez haosa</p>

    @auth
        <div class="hero-actions">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('tickets.admin-index') }}" class="btn btn-primary">
                    📋 Skatīt visas biļetes
                </a>
                <a href="{{ route('tickets.calendar') }}" class="btn btn-secondary">
                    📅 Atvērt kalendāru
                </a>
            @else
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    ✏️ Izveidot biļeti
                </a>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    👁️ Manas biļetes
                </a>
            @endif
        </div>
    @else
        <div class="hero-actions">
            <a href="{{ route('login') }}" class="btn btn-primary">
                Pieslēgities
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary">
                Reģistrēties
            </a>
        </div>
    @endauth
</div>

<!-- FEATURES -->
<div class="features">
    <div class="feature-card">
        <h3>⚡ Ātra biļešu izveide</h3>
        <p>Izveido problēmu dažu sekunžu laikā un nekavējoties sāc tās risināšanu.</p>
    </div>

    <div class="feature-card">
        <h3>📌 Prioritāšu sistēma</h3>
        <p>Atzīmē steidzamas problēmas un pārliecinies, ka svarīgākais tiek risināts pirmais.</p>
    </div>

    <div class="feature-card">
        <h3>💬 Komentāri & komunikācija</h3>
        <p>Sazinies vienkārši tieši biļetē bez e-pastiem un lieka haosa.</p>
    </div>

    <div class="feature-card">
        <h3>📎 Failu pielikumi</h3>
        <p>Pievieno ekrānšāviņus un failus, lai problēmu saprastu ātrāk.</p>
    </div>

    <div class="feature-card">
        <h3>📊 Statusa izsekošana</h3>
        <p>Redzi, kas notiek ar tavu biļeti reāllaikā bez minēšanas spēlēm.</p>
    </div>
</div>

@endsection