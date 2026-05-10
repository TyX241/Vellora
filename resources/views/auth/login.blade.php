@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card bg-dark border-secondary">
            <div class="card-body p-4">
                <h3 class="text-center mb-4 text-warning">Login</h3>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control bg-secondary text-light border-0" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control bg-secondary text-light border-0" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <small>Belum punya akun? <a href="{{ route('register') }}" class="text-warning">Daftar di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection