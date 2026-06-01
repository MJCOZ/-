@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي — MJ TV')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-gear-fill text-warning fs-3"></i>
        <h2 class="mb-0">تعديل الملف الشخصي</h2>
    </div>

    <div class="row g-4">
        {{-- البيانات الأساسية --}}
        <div class="col-md-6">
            <div class="bg-dark-2 p-4 rounded h-100">
                <h5 class="mb-3"><i class="bi bi-person text-warning"></i> البيانات الأساسية</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn btn-warning"><i class="bi bi-check-lg"></i> حفظ البيانات</button>
                </form>
            </div>
        </div>

        {{-- كلمة المرور --}}
        <div class="col-md-6">
            <div class="bg-dark-2 p-4 rounded h-100">
                <h5 class="mb-3"><i class="bi bi-key text-warning"></i> تغيير كلمة المرور</h5>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">كلمة المرور الحالية</label>
                        <input type="password" name="current_password" required
                               class="form-control bg-dark text-light border-secondary @error('current_password') is-invalid @enderror">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="password" required
                               class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="password_confirmation" required
                               class="form-control bg-dark text-light border-secondary">
                    </div>

                    <button class="btn btn-warning"><i class="bi bi-shield-lock"></i> تحديث كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('profile') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right"></i> رجوع للملف الشخصي</a>
    </div>

@endsection
