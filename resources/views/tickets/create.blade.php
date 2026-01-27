@extends('layouts.app')

@section('title', 'Jauna biļete')

@section('content')
<div class="breadcrumb">
    <a href="/">Sākums</a> / <a href="{{ route('tickets.index') }}">Manas biļetes</a> / Jauna biļete
</div>

<div class="card">
    <div class="card-header">
        <h2>Jauna IT biļete</h2>
    </div>

    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
        @csrf

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

<script>
const fileInput = document.getElementById('attachments');
const fileList = document.getElementById('file-list');

fileInput.addEventListener('change', function() {
    updateFileList();
});

function updateFileList() {
    fileList.innerHTML = '';
    if (fileInput.files.length > 0) {
        const list = document.createElement('ul');
        list.className = 'attachments-list';
        
        for (let i = 0; i < fileInput.files.length; i++) {
            const file = fileInput.files[i];
            const li = document.createElement('li');
            li.innerHTML = `
                <span>📎 ${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                <button type="button" onclick="removeFile(${i})" class="btn btn-danger btn-small">Noņemt</button>
            `;
            list.appendChild(li);
        }
        
        fileList.appendChild(list);
    }
}

// Drag and drop
const dropZone = document.querySelector('.file-upload-box');
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.backgroundColor = '#e8f4f8';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.backgroundColor = '';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.backgroundColor = '';
    fileInput.files = e.dataTransfer.files;
    updateFileList();
});
</script>
@endsection
