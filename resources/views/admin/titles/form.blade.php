@extends('layouts.app')

@php $editing = $title->exists; @endphp

@section('title', ($editing ? 'تعديل' : 'إضافة') . ' عمل — CineReview')

@section('content')
    <h2 class="mb-4">
        <i class="bi bi-film text-warning"></i>
        {{ $editing ? 'تعديل: ' . $title->name : 'إضافة عمل جديد' }}
    </h2>
    @include('admin.partials.nav', ['active' => 'titles'])

    <div class="bg-dark-2 p-4 rounded">
        <form method="POST"
              action="{{ $editing ? route('admin.titles.update', $title) : route('admin.titles.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $title->name) }}" required
                           class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">النوع</label>
                    <select name="type" class="form-select bg-dark text-light border-secondary">
                        <option value="movie" @selected(old('type', $title->type) === 'movie')>فيلم</option>
                        <option value="series" @selected(old('type', $title->type) === 'series')>مسلسل</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">التصنيف</label>
                    <select name="genre_id" class="form-select bg-dark text-light border-secondary">
                        <option value="">— بدون —</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" @selected(old('genre_id', $title->genre_id) == $genre->id)>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">سنة الإصدار</label>
                    <input type="number" name="release_year" value="{{ old('release_year', $title->release_year) }}"
                           min="1900" max="2100"
                           class="form-control bg-dark text-light border-secondary @error('release_year') is-invalid @enderror">
                    @error('release_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">رابط البوستر <small class="text-secondary">(اختياري — يُولّد تلقائياً إن تُرك فارغاً)</small></label>
                    <input type="text" name="poster" value="{{ old('poster', $title->poster) }}"
                           class="form-control bg-dark text-light border-secondary" placeholder="https://...">
                </div>

                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="4"
                              class="form-control bg-dark text-light border-secondary">{{ old('description', $title->description) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-check-lg"></i> {{ $editing ? 'حفظ التعديلات' : 'إضافة' }}</button>
                <a href="{{ route('admin.titles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
@endsection
