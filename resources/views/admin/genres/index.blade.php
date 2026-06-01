@extends('layouts.app')

@php $editing = $genre->exists; @endphp

@section('title', 'إدارة التصنيفات — CineReview')

@section('content')
    <h2 class="mb-4"><i class="bi bi-tags text-warning"></i> إدارة التصنيفات</h2>
    @include('admin.partials.nav', ['active' => 'genres'])

    <div class="row g-4">
        {{-- نموذج الإضافة/التعديل --}}
        <div class="col-md-4">
            <div class="bg-dark-2 p-4 rounded">
                <h5 class="mb-3">{{ $editing ? 'تعديل تصنيف' : 'إضافة تصنيف' }}</h5>
                <form method="POST"
                      action="{{ $editing ? route('admin.genres.update', $genre) : route('admin.genres.store') }}">
                    @csrf
                    @if ($editing) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف</label>
                        <input type="text" name="name" value="{{ old('name', $genre->name) }}" required
                               class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-warning">{{ $editing ? 'حفظ' : 'إضافة' }}</button>
                        @if ($editing)
                            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- قائمة التصنيفات --}}
        <div class="col-md-8">
            <div class="bg-dark-2 rounded p-3">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr><th>التصنيف</th><th>عدد الأعمال</th><th class="text-end">إجراءات</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($genres as $g)
                            <tr>
                                <td>{{ $g->name }}</td>
                                <td>{{ $g->titles_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.genres.edit', $g) }}" class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.genres.destroy', $g) }}" class="d-inline"
                                          onsubmit="return confirm('حذف تصنيف «{{ $g->name }}»؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-secondary">لا توجد تصنيفات.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
