@extends('layouts.app')

@push('styles')
<style>
.auth-wrap { display: flex; justify-content: center; padding: 20px 0 60px; }
.auth-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
}
.auth-logo {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px;
    letter-spacing: 3px;
    color: var(--gold);
    text-align: center;
    margin-bottom: 8px;
}
.auth-tagline { text-align: center; font-size: 14px; color: var(--text-muted); margin-bottom: 32px; }
.auth-title { font-size: 22px; font-weight: 600; color: var(--text-primary); margin-bottom: 24px; }
.auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted); }
.auth-footer a { color: var(--gold); text-decoration: none; font-weight: 500; }
.auth-footer a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">VELLORA</div>
        <p class="auth-tagline">Katalog Film & Series</p>

        <h2 class="auth-title">Masuk ke Akun</h2>

        @if ($errors->any())
            <div class="v-alert v-alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="v-form-group">
                <label class="v-label">Alamat Email</label>
                <input type="email" name="email" class="v-input" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            </div>
            <div class="v-form-group">
                <label class="v-label">Password</label>
                <input type="password" name="password" class="v-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-gold" style="width:100%;justify-content:center;font-size:15px;padding:11px;">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </div>
</div>
@endsection
