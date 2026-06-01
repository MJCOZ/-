@extends('layouts.app')

@section('title', 'تسجيل الدخول — MJ TV')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="bg-dark-2 p-4 rounded shadow">
            <h3 class="text-center mb-4"><i class="bi bi-box-arrow-in-left text-warning"></i> تسجيل الدخول</h3>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" required
                           class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label class="form-check-label" for="remember">تذكّرني</label>
                </div>

                <div class="d-grid">
                    <button class="btn btn-warning"><i class="bi bi-box-arrow-in-left"></i> دخول</button>
                </div>
            </form>

            <p class="text-center text-secondary mt-3 mb-0">
                ما عندك حساب؟ <a href="{{ route('register') }}" class="text-warning">أنشئ حساب جديد</a>
            </p>
        </div>
    </div>
</div>
@endsection
