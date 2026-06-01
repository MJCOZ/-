@extends('layouts.app')

@section('title', 'إنشاء حساب — MJ TV')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="bg-dark-2 p-4 rounded shadow">
            <h3 class="text-center mb-4"><i class="bi bi-person-plus text-warning"></i> إنشاء حساب جديد</h3>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" required
                           class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" required
                           class="form-control bg-dark text-light border-secondary">
                </div>

                <div class="d-grid">
                    <button class="btn btn-warning"><i class="bi bi-check-circle"></i> إنشاء الحساب</button>
                </div>
            </form>

            <p class="text-center text-secondary mt-3 mb-0">
                عندك حساب؟ <a href="{{ route('login') }}" class="text-warning">سجّل دخولك</a>
            </p>
        </div>
    </div>
</div>
@endsection
