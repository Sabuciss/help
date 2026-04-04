@extends('layouts.app')

@section('title', 'IT Help Desk - Mājas lapa')

@section('content')
<div style="text-align: center; padding: 3rem 0;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">🎫 IT Help Desk System</h1>
    <p style="font-size: 1.2rem; color: #7f8c8d; margin-bottom: 2rem;">Iesūtiet IT problēmas un saņemiet ātru atbalstu no mūsu IT personāla</p>

    @auth
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem;">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('tickets.admin-index') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    📋 Visas biļetes
                </a>
                <a href="{{ route('tickets.calendar') }}" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    📅 Kalendārs
                </a>
            @else
                <a href="{{ route('tickets.create') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    ✏️ Iesūtīt jaunu biļeti
                </a>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    👁️ Manas biļetes
                </a>
            @endif
        </div>
    @else
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem;">
            <a href="{{ route('login') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                Pieslēgties
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                Reģistrēties
            </a>
        </div>
    @endauth
</div>

<!-- Features -->
<div class="grid" style="margin-top: 3rem;">
    <div class="card" style="text-align: center;">
        <h3>👤 Lietotāji</h3>
        <ul style="text-align: left; padding-left: 1rem; line-height: 2;">
            <li>✓ Izveidot IT biļetes</li>
            <li>✓ Apskatīt savas biļetes</li>
            <li>✓ Rediģēt biļetes</li>
            <li>✓ Pievienot pielikumus</li>
            <li>✓ Komentēt biļetes</li>
        </ul>
    </div>

    <div class="card" style="text-align: center;">
        <h3>👨‍💼 IT Personāls</h3>
        <ul style="text-align: left; padding-left: 1rem; line-height: 2;">
            <li>✓ Redzēt visas biļetes</li>
            <li>✓ Mainīt biļešu statusu</li>
            <li>✓ Piešķirt biļetes</li>
            <li>✓ Komentēt biļetes</li>
            <li>✓ Biļešu kalendārs</li>
        </ul>
    </div>

    <div class="card" style="text-align: center;">
        <h3>📊 Funkcionalitāte</h3>
        <ul style="text-align: left; padding-left: 1rem; line-height: 2;">
            <li>✓ Prioritāšu sistēma</li>
            <li>✓ Statusa izsekošana</li>
            <li>✓ Failu pielikumi</li>
            <li>✓ Komentāru sistēma</li>
            <li>✓ Meklēšana un filtrēšana</li>
        </ul>
    </div>

    <div class="card" style="text-align: center;">
        <h3>🔒 Drošība</h3>
        <ul style="text-align: left; padding-left: 1rem; line-height: 2;">
            <li>✓ Lietotāju autentifikācija</li>
            <li>✓ Lomu un atļauju sistēma</li>
            <li>✓ Drošas datu pārsūtīšana</li>
            <li>✓ CSRF aizsardzība</li>
            <li>✓ Datu validācija</li>
        </ul>
    </div>
</div>

@endsection
