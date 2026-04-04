@extends('layouts.app')

@section('title', 'Rediģēt biļeti')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Rediģēt biļeti</h2>
    </div>

    <form method="POST" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="first_name">Vārds *</label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $ticket->first_name) }}" required>
            @error('first_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="last_name">Uzvārds *</label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $ticket->last_name) }}" required>
            @error('last_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="class_department">Klase / Nodaļa *</label>
            <input type="text" id="class_department" name="class_department" value="{{ old('class_department', $ticket->class_department) }}" required>
            @error('class_department')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">Kategorija *</label>
            <select id="category" name="category" required>
                <option value="hardware" {{ old('category', $ticket->category) == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ old('category', $ticket->category) == 'software' ? 'selected' : '' }}>Software</option>
                <option value="network" {{ old('category', $ticket->category) == 'network' ? 'selected' : '' }}>Network</option>
                <option value="other" {{ old('category', $ticket->category) == 'other' ? 'selected' : '' }}>Cits</option>
            </select>
            @error('category')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Problēmas nosaukums *</label>
            <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Detalizēts apraksts *</label>
            <textarea id="description" name="description" required>{{ old('description', $ticket->description) }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="priority">Prioritāte *</label>
            <select id="priority" name="priority" required>
                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Zema</option>
                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Vidēja</option>
                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>Augsta</option>
                <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Steidzama</option>
            </select>
            @error('priority')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="attachments">Pielikumi (Attēli, Dokumenti)</label>

            @if($ticket->attachments->count() > 0)
                <div style="margin-bottom: 1rem;">
                    <strong>Esošie pielikumi:</strong>
                    <ul class="attachments-list" style="margin-top: 0.5rem;">
                        @foreach($ticket->attachments as $attachment)
                            <li data-attachment-id="{{ $attachment->id }}">
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
                                <div>
                                    <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-primary btn-small">Lejupielādēt</a>
                                    <button type="button" onclick="deleteAttachment({{ $attachment->id }}, '{{ $attachment->file_name }}')" class="btn btn-danger btn-small">Noņemt</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="file-upload-box" onclick="document.getElementById('attachments').click();">
                <p>Noklikšķiniet, lai augšupielādētu failus vai vienkārši pārvelciet tos šeit</p>
                <small style="color: #7f8c8d;">Maksimums 10MB uz failu</small>
            </div>
            <input type="file" id="attachments" name="attachments[]" multiple style="display: none;" accept="image/*,.pdf,.doc,.docx">
            <div id="file-list" style="margin-top: 1rem;"></div>
            @error('attachments')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Atcelt</a>
        </div>
    </form>
</div>
@endsection
