@extends('layouts.app')

@php $editing = $tag->exists; @endphp

@section('title', 'إدارة الوسوم — CineReview')

@section('content')
    <h2 class="mb-4"><i class="bi bi-hash text-warning"></i> إدارة الوسوم</h2>
    @include('admin.partials.nav', ['active' => 'tags'])

    <div class="row g-4">
        <div class="col-md-4">
            <div class="bg-dark-2 p-4 rounded">
                <h5 class="mb-3">{{ $editing ? 'تعديل وسم' : 'إضافة وسم' }}</h5>
                <form method="POST"
                      action="{{ $editing ? route('admin.tags.update', $tag) : route('admin.tags.store') }}">
                    @csrf
                    @if ($editing) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">اسم الوسم</label>
                        <input type="text" name="name" value="{{ old('name', $tag->name) }}" required
                               class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-warning">{{ $editing ? 'حفظ' : 'إضافة' }}</button>
                        @if ($editing)
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-dark-2 rounded p-3">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr><th>الوسم</th><th>عدد الأعمال</th><th class="text-end">إجراءات</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($tags as $t)
                            <tr>
                                <td><span class="badge bg-info text-dark">#{{ $t->name }}</span></td>
                                <td>{{ $t->titles_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.tags.edit', $t) }}" class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.tags.destroy', $t) }}" class="d-inline"
                                          onsubmit="return confirm('حذف وسم «{{ $t->name }}»؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-secondary">لا توجد وسوم.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
