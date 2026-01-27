@extends('layouts.app')

@section('title', $ticket->title)

@section('content')
<div class="breadcrumb">
    <a href="/">Sākums</a> / 
    @if(Auth::user()->isAdmin())
        <a href="{{ route('tickets.admin-index') }}">Visas biļetes</a>
    @else
        <a href="{{ route('tickets.index') }}">Manas biļetes</a>
    @endif
    / {{ $ticket->title }}
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2>{{ $ticket->title }}</h2>
            <small style="color: #7f8c8d;">Biļete #{{ $ticket->id }} - {{ $ticket->created_at->format('d.m.Y H:i') }}</small>
        </div>
        <div style="display: flex; gap: 0.5rem;">
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
            <span class="badge badge-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }} prioritāte</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Main content -->
        <div>
            <h3 style="margin-bottom: 1rem;">Problēma</h3>
            <p style="line-height: 1.6; color: #555;">{{ nl2br(e($ticket->description)) }}</p>

            <!-- Attachments -->
            @if($ticket->attachments->count() > 0)
                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Pielikumi</h3>
                <ul class="attachments-list">
                    @foreach($ticket->attachments as $attachment)
                        <li>
                            <span>
                                @if(str_contains($attachment->mime_type, 'image'))
                                    🖼️
                                @elseif($attachment->mime_type === 'application/pdf')
                                    📄
                                @else
                                    📎
                                @endif
                                {{ $attachment->file_name }} 
                                <small>({{ number_format($attachment->size / 1024, 2) }} KB)</small>
                            </span>
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn btn-primary btn-small" download>Lejupielādēt</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <div style="background-color: #ecf0f1; padding: 1.5rem; border-radius: 5px;">
                <h4 style="margin-bottom: 1rem;">Informācija</h4>
                
                <p style="margin-bottom: 1rem;">
                    <strong>Autors:</strong><br>
                    {{ $ticket->user->name }}<br>
                    <small style="color: #7f8c8d;">{{ $ticket->user->email }}</small>
                </p>

                @if($ticket->assignedTo)
                    <p style="margin-bottom: 1rem;">
                        <strong>Piešķirts:</strong><br>
                        {{ $ticket->assignedTo->name }}
                    </p>
                @endif

                <p style="margin-bottom: 1rem;">
                    <strong>Izveidota:</strong><br>
                    {{ $ticket->created_at->format('d.m.Y H:i') }}
                </p>

                <p style="margin-bottom: 1rem;">
                    <strong>Pēdējā atjauninājuma:</strong><br>
                    {{ $ticket->updated_at->format('d.m.Y H:i') }}
                </p>

                <!-- Admin controls -->
                @if(Auth::user()->isAdmin())
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #bdc3c7;">
                        <h4 style="margin-bottom: 1rem;">Administrācija</h4>

                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}" style="margin-bottom: 1rem;">
                            @csrf
                            @method('PATCH')
                            <label style="font-size: 0.9rem;">Mainīt statusu:</label>
                            <select name="status" required style="font-size: 0.9rem;">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Atvērta</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Tiek risināta</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Atrisināta</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Slēgta</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-small" style="width: 100%; margin-top: 0.5rem;">Atjaunināt</button>
                        </form>

                        <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Vai tiešam dzēst šo biļeti?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small" style="width: 100%;">Dzēst biļeti</button>
                        </form>
                    </div>
                @elseif(Auth::user()->id === $ticket->user_id && $ticket->status !== 'closed')
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #bdc3c7;">
                        <div style="display: flex; gap: 0.5rem; flex-direction: column;">
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-secondary btn-small">Rediģēt</a>
                            <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Vai tiešam dzēst?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small" style="width: 100%;">Dzēst</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Comments -->
    <div class="comments">
        <h3 style="margin-bottom: 1.5rem;">Komentāri ({{ $ticket->comments->count() }})</h3>

        @if($ticket->comments->count() > 0)
            @foreach($ticket->comments as $comment)
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user->name }}</span>
                        <span>{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                        @if(Auth::user()->id === $comment->user_id || Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline;" onsubmit="return confirm('Dzēst komentāru?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 0.85rem;">Dzēst</button>
                            </form>
                        @endif
                    </div>
                    <p>{{ nl2br(e($comment->comment)) }}</p>
                </div>
            @endforeach
        @else
            <p style="color: #7f8c8d; text-align: center; padding: 2rem;">Nav komentāru. Pievieno pirmo!</p>
        @endif

        <form method="POST" action="{{ route('comments.store', $ticket) }}" style="margin-top: 2rem;">
            @csrf
            <div class="form-group">
                <label for="comment">Pievienot komentāru</label>
                <textarea id="comment" name="comment" required placeholder="Rakstiet vēstuli..."></textarea>
                @error('comment')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Pievienot komentāru</button>
        </form>
    </div>
</div>
@endsection
