@extends('layouts.app')

@section('title', 'Manas biļetes')

@section('content')
<div class="card-header" style="margin-bottom: 2rem;">
    <h2>Manas biļetes</h2>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Jauna biļete</a>
</div>

@if($tickets->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Nosaukums</th>
                <th>Prioritāte</th>
                <th>Statuss</th>
                <th>Izveidota</th>
                <th>Darbības</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" style="color: #3498db; text-decoration: none;">
                            {{ $ticket->title }}
                        </a>
                    </td>
                    <td>
                        <span class="badge badge-{{ $ticket->priority }}">
                            {{ ucfirst($ticket->priority) }}
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
                    <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-primary btn-small">Redzēt</a>
                            @if($ticket->status !== 'closed')
                                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-secondary btn-small">Rediģēt</a>
                                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" style="display:inline;" onsubmit="return confirm('Vai tiešam dzēst?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Dzēst</button>
                                </form>
                            @endif
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
        <p style="color: #7f8c8d; font-size: 1.1rem;">Jums vēl nav izveidotu biļešu.</p>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Izveidot pirmo biļeti</a>
    </div>
@endif
@endsection
