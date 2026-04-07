@extends('layouts.app')

@section('title', 'Biļešu kalendārs')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Biļešu skaits pa dienām</h2>
        <a href="{{ route('tickets.calendar-export') }}" class="btn btn-secondary">Eksportēt PDF</a>
    </div>

    <div id="calendar" style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin: 2rem 0;">
        <!-- Calendar will be generated here -->
    </div>
</div>

<div class="card">
    <h3>Darbības noslodze</h3>
    <div class="grid">
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['total'] }}</div>
            <div class="stat-box-label">Kopējās biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['urgent'] }}</div>
            <div class="stat-box-label">Steidzamas biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['open'] }}</div>
            <div class="stat-box-label">Neizpildītas biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['closed'] }}</div>
            <div class="stat-box-label">Noslēgtas biļetes</div>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JavaScript
    window.calendarData = @json($calendarTickets);
    const tickets = window.calendarData;
    console.log(tickets[0]);
</script>
@endsection
