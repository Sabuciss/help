@extends('layouts.app')

@section('title', 'Reģistrācija')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <div class="card">
        <div class="card-header">
            <h2>Reģistrācija</h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Vārds</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">E-pasta adrese</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Parole</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Apstipriniet paroli</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Reģistrēties</button>

            <p style="text-align: center; color: #7f8c8d;">
                Jau esat reģistrējies? <a href="{{ route('login') }}" style="color: #3498db; text-decoration: none;">Pieslēgties</a>
            </p>
        </form>
    </div>
</div>
@endsection
