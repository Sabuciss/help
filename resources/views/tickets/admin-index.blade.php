@extends('layouts.app')

@section('title', 'Visas biļetes')

@section('content')
<div class="breadcrumb">
    <a href="/">Sākums</a> / Visas biļetes
</div>

<!-- Dashboard Stats -->
<div class="grid">
    <div class="stat-box">
        <div class="stat-box-number">{{ Illuminate\Support\Facades\DB::table('tickets')->where('status', 'open')->count() }}</div>
        <div class="stat-box-label">Atvērtas biļetes</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-number">{{ Illuminate\Support\Facades\DB::table('tickets')->where('status', 'in_progress')->count() }}</div>
        <div class="stat-box-label">Tiek risinātas</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-number">{{ Illuminate\Support\Facades\DB::table('tickets')->where('status', 'resolved')->count() }}</div>
        <div class="stat-box-label">Atrisinātas</div>
    </div>
    <div class="stat-box">
        <div class="stat-box-number">{{ Illuminate\Support\Facades\DB::table('tickets')->where('status', 'urgent')->count() }}</div>
        <div class="stat-box-label">Steidzamas</div>
    </div>
</div>

<div class="card-header" style="margin-bottom: 2rem;">
    <h2>IT biļetes - Administratīvais skatījums</h2>
    <a href="{{ route('tickets.calendar') }}" class="btn btn-secondary">Kalendāra skats</a>
</div>

@if($tickets->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nosaukums</th>
                <th>Autors</th>
                <th>Prioritāte</th>
                <th>Statuss</th>
                <th>Piešķirts</th>
                <th>Izveidota</th>
                <th>Darbības</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" style="color: #3498db; text-decoration: none;">
                            {{ Illuminate\Support\Str::limit($ticket->title, 30) }}
                        </a>
                    </td>
                    <td>
                        <small>{{ $ticket->user->name }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $ticket->priority }}">
                            @switch($ticket->priority)
                                @case('low')
                                    Zema
                                    @break
                                @case('medium')
                                    Vidēja
                                    @break
                                @case('high')
                                    Augsta
                                    @break
                                @case('urgent')
                                    Steidzama
                                    @break
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $ticket->status }}">
                            @switch($ticket->status)
                                @case('open')
                                    Atvērta
                                    @break
                                @case('in_progress')
                                    Tiek risināta
                                    @break
                                @case('resolved')
                                    Atrisināta
                                    @break
                                @case('closed')
                                    Slēgta
                                    @break
                            @endswitch
                        </span>
                    </td>
                    <td>
                        @if($ticket->assignedTo)
                            <small>{{ $ticket->assignedTo->name }}</small>
                        @else
                            <small style="color: #e74c3c;">Nav piešķirta</small>
                        @endif
                    </td>
                    <td>
                        <small>{{ $ticket->created_at->format('d.m.Y') }}</small>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-primary btn-small">Redzēt</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $tickets->links() }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #7f8c8d; font-size: 1.1rem;">Nav nevienas biļetes.</p>
    </div>
@endif
@endsection
