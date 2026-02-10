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

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Atcelt</a>
        </div>
    </form>
</div>
@endsection
