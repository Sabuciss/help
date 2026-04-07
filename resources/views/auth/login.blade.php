@extends('layouts.app')

@section('title', 'Pieslēgties')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <div class="card">
        <div class="card-header">
            <h2>Pieslēgties</h2>
        </div><br>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">E-pasta adrese</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
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

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Pieslēgties</button>

            <p style="text-align: center; color: #7f8c8d;">
                Nav konts? <a href="{{ route('register') }}" style="color: #3498db; text-decoration: none;">Reģistrējieties</a>
            </p>
        </form>
    </div>

    <div style="margin-top: 2rem; padding: 1.5rem; background-color: #ecf0f1; border-radius: 5px; text-align: center;">
        <p style="margin-bottom: 1rem; font-weight: bold;">Test konti:</p>
        <p style="margin: 0.5rem 0; font-size: 0.9rem;">
            <strong>Lietotājs:</strong> janis@example.com<br>
            <strong>Parole:</strong> password
        </p>
        <p style="margin: 0.5rem 0; font-size: 0.9rem;">
            <strong>Admin:</strong> anna@example.com<br>
            <strong>Parole:</strong> password
        </p>
    </div>
</div>
@endsection
