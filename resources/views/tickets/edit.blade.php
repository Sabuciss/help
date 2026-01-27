@extends('layouts.app')

@section('title', 'Rediģēt biļeti')

@section('content')
<div class="breadcrumb">
    <a href="/">Sākums</a> / <a href="{{ route('tickets.index') }}">Manas biļetes</a> / <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a> / Rediģēt
</div>

<div class="card">
    <div class="card-header">
        <h2>Rediģēt biļeti</h2>
    </div>

    <form method="POST" action="{{ route('tickets.update', $ticket) }}">
        @csrf
        @method('PATCH')

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

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Atcelt</a>
        </div>
    </form>
</div>
@endsection
