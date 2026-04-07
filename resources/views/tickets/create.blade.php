@extends('layouts.app')

@section('title', 'Jauna biļete')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Jauna IT biļete</h2>
    </div><br>

    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="first_name">Vārds *</label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            @error('first_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="last_name">Uzvārds *</label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="class_department">Klase / Nodaļa *</label>
            <input type="text" id="class_department" name="class_department" value="{{ old('class_department') }}" required placeholder="Piemērs: 301. kabinets / Grāmatvedība">
            @error('class_department')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">Kategorija *</label>
            <select id="category" name="category" required>
                <option value="hardware" {{ old('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="network" {{ old('category') == 'network' ? 'selected' : '' }}>Network</option>
                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Cits</option>
            </select>
            @error('category')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Problēmas nosaukums *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Īss problēmas apraksts">
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Detalizēts apraksts *</label>
            <textarea id="description" name="description" required placeholder="Detalizējiet jūsu IT problēmu...">{{ old('description') }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="priority">Prioritāte *</label>
            <select id="priority" name="priority" required>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Zema</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Vidēja</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Augsta</option>
                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Steidzama</option>
            </select>
            @error('priority')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="attachments">Pielikumi (Attēli, Dokumenti)</label>
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
            <button type="submit" class="btn btn-primary">Iesūtīt biļeti</button>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Atcelt</a>
        </div>
    </form>
</div>
@endsection
