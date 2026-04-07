@extends('layouts.app')

@section('title', 'Visas biļetes')

@section('content')
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
        <div class="stat-box-number">{{ Illuminate\Support\Facades\DB::table('tickets')->where('priority', 'urgent')->count() }}</div>
        <div class="stat-box-label">Steidzamas</div>
    </div>
</div>

<div class="card-header" style="margin-bottom: 2rem;">
    <h2 style="margin-bottom:10px;">IT biļetes - Administratīvais skatījums</h2>
    <a href="{{ route('tickets.calendar') }}" class="btn btn-secondary">Kalendāra skats</a>
</div>

<!-- Filter Form -->
<div class="card" style="margin-bottom: 2rem;">
    <form method="GET" action="{{ route('tickets.admin-index') }}" class="filter-form">
        <div class="form-group">
            <label for="filter_id">ID</label>
            <input type="number" id="filter_id" name="id" value="{{ request('id') }}" placeholder="#">
        </div>
        <div class="form-group">
            <label for="filter_status">Statuss</label>
            <select id="filter_status" name="status">
                <option value="">Visi</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Atvērta</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Tiek risināta</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Atrisināta</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Slēgta</option>
            </select>
        </div>
        <div class="form-group">
            <label for="filter_category">Kategorija</label>
            <select id="filter_category" name="category">
                <option value="">Visas</option>
                <option value="hardware" {{ request('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ request('category') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="network" {{ request('category') == 'network' ? 'selected' : '' }}>Network</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Cits</option>
            </select>
        </div>
        <div class="form-group">
            <label for="filter_priority">Prioritāte</label>
            <select id="filter_priority" name="priority">
                <option value="">Visas</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Zema</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Vidēja</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Augsta</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Steidzama</option>
            </select>
        </div>
        <div class="form-group" style="align-self: end;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrēt</button>
        </div>
    </form>
</div>

<!-- Ticket output -->
@if($tickets->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nosaukums</th>
                <th>Autors</th>
                <th>Klase / Nodaļa</th>
                <th>Kategorija</th>
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
                        <small>{{ $ticket->class_department ?? '-' }}</small>
                    </td>
                    <td>
                        <small>{{ $ticket->category ? ucfirst($ticket->category) : '-' }}</small>
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

    <!-- mobile card view -->
    <div class="tickets-cards">
        @foreach($tickets as $ticket)
            <div class="ticket-card">
                <!-- Header -->
                <div class="ticket-card-header">
                    <span class="ticket-id"><strong>#{{ $ticket->id }}</strong></span>
                    <span class="ticket-title"><strong>{{ $ticket->title }}</strong></span>
                    <span class="badge badge-{{ $ticket->priority }}" style="margin-bottom:10px;">
                        @switch($ticket->priority)
                            @case('low') Zema @break
                            @case('medium') Vidēja @break
                            @case('high') Augsta @break
                            @case('urgent') Steidzama @break
                        @endswitch
                    </span>
                </div>

                <!-- Info rows -->
                <p><strong>Autors:</strong> {{ $ticket->user->name }}</p>
                <p><strong>Klase / Nodaļa:</strong> {{ $ticket->class_department ?? '-' }}</p>
                <p><strong>Kategorija:</strong> {{ $ticket->category ? ucfirst($ticket->category) : '-' }}</p>

                <p>
                    <strong class="ticket-status">Statuss:</strong>
                    <span class="badge badge-{{ $ticket->status }}" style="margin:5px 0;">
                        @switch($ticket->status)
                            @case('open') Atvērta @break
                            @case('in_progress') Tiek risināta @break
                            @case('resolved') Atrisināta @break
                            @case('closed') Slēgta @break
                        @endswitch
                    </span>
                </p>

                <p>
                    <strong>Piešķirts:</strong>
                    @if($ticket->assignedTo)
                        {{ $ticket->assignedTo->name }}
                    @else
                        <span style="color: #e74c3c;">Nav piešķirta</span>
                    @endif
                </p>

                <p><strong>Izveidota:</strong> {{ $ticket->created_at->format('d.m.Y') }}</p>

                <!-- Footer -->
                <div class="ticket-card-footer">
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-primary btn-small" style="margin-top:20px;">Redzēt</a>
                </div>
            </div>
        @endforeach
    </div>

    <div>
        {{ $tickets->links() }}
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #7f8c8d; font-size: 1.1rem;">Nav nevienas biļetes.</p>
    </div>
@endif
@endsection
