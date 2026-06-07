@extends('layouts.app')

@php $editing = $title->exists; @endphp

@section('title', ($editing ? 'تعديل' : 'إضافة') . ' عمل — MJCOZ TV')

@section('content')
    <h2 class="mb-4">
        <i class="bi bi-film text-warning"></i>
        {{ $editing ? 'تعديل: ' . $title->name : 'إضافة عمل جديد' }}
    </h2>
    @include('admin.partials.nav', ['active' => 'titles'])

    <div class="bg-dark-2 p-4 rounded">
        <form method="POST" enctype="multipart/form-data"
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

                <div class="col-12">
                    <label class="form-label">التصنيفات <small class="text-secondary">(اختر واحداً أو أكثر)</small></label>
                    @php $selectedGenres = old('genres', isset($title) ? $title->genres->pluck('id')->all() : []); @endphp
                    @if ($genres->isEmpty())
                        <div class="text-secondary small">لا توجد تصنيفات بعد —
                            <a href="{{ route('admin.genres.index') }}" class="text-warning">أضف تصنيفات أولاً</a>.</div>
                    @else
                    <div class="d-flex flex-wrap gap-3 bg-dark p-3 rounded border border-secondary @error('genres') border-danger @enderror">
                        @foreach ($genres as $genre)
                            <div class="form-check">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                       id="genre{{ $genre->id }}" class="form-check-input"
                                       @checked(in_array($genre->id, $selectedGenres))>
                                <label class="form-check-label" for="genre{{ $genre->id }}">{{ $genre->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    @error('genres.*') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">سنة الإصدار</label>
                    <input type="number" name="release_year" value="{{ old('release_year', $title->release_year) }}"
                           min="1900" max="2100" dir="ltr" lang="en" inputmode="numeric"
                           class="form-control bg-dark text-light border-secondary @error('release_year') is-invalid @enderror">
                    @error('release_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">رفع صورة البوستر <small class="text-secondary">(JPG/PNG/WEBP حتى 4MB)</small></label>
                    <input type="file" name="poster_file" accept="image/*"
                           class="form-control bg-dark text-light border-secondary @error('poster_file') is-invalid @enderror">
                    @error('poster_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text text-secondary">أو ضع رابطاً خارجياً بالأسفل. لو تركت الكل فارغاً يُولّد بوستر تلقائي.</div>
                    <input type="text" name="poster" value="{{ old('poster', $title->poster) }}"
                           class="form-control bg-dark text-light border-secondary mt-2" placeholder="https://...">
                </div>
                <div class="col-md-4 text-center">
                    <label class="form-label d-block">المعاينة الحالية</label>
                    <img src="{{ $title->posterUrl() }}" alt="poster" class="img-fluid rounded" style="max-height: 180px;">
                </div>

                <div class="col-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="4"
                              class="form-control bg-dark text-light border-secondary">{{ old('description', $title->description) }}</textarea>
                </div>

                {{-- منصة العرض ورابط المشاهدة --}}
                <div class="col-md-5">
                    <label class="form-label">📺 منصة العرض <small class="text-secondary">(اختياري)</small></label>
                    <input type="text" name="platform" value="{{ old('platform', $title->platform) }}"
                           list="platforms" placeholder="Netflix، شاهد، Disney+..."
                           class="form-control bg-dark text-light border-secondary">
                    <datalist id="platforms">
                        <option value="Netflix"><option value="شاهد"><option value="Disney+">
                        <option value="Amazon Prime"><option value="OSN+"><option value="Apple TV+"><option value="HBO Max">
                    </datalist>
                </div>
                <div class="col-md-7">
                    <label class="form-label">🔗 رابط المشاهدة <small class="text-secondary">(اختياري)</small></label>
                    <input type="url" name="watch_url" value="{{ old('watch_url', $title->watch_url) }}"
                           placeholder="https://..."
                           class="form-control bg-dark text-light border-secondary @error('watch_url') is-invalid @enderror">
                    @error('watch_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">▶️ رابط الإعلان (تريلر يوتيوب) <small class="text-secondary">(اختياري)</small></label>
                    <input type="url" name="trailer_url" value="{{ old('trailer_url', $title->trailer_url) }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="form-control bg-dark text-light border-secondary @error('trailer_url') is-invalid @enderror">
                    @error('trailer_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- الوسوم المتعددة --}}
                <div class="col-12">
                    <label class="form-label">الوسوم <small class="text-secondary">(اختر واحداً أو أكثر)</small></label>
                    @php $selectedTags = old('tags', $title->tags->pluck('id')->all()); @endphp
                    @if ($allTags->isEmpty())
                        <div class="text-secondary small">لا توجد وسوم بعد —
                            <a href="{{ route('admin.tags.index') }}" class="text-warning">أضف وسوماً</a>.</div>
                    @else
                        <div class="d-flex flex-wrap gap-3 bg-dark p-3 rounded border border-secondary">
                            @foreach ($allTags as $tag)
                                <div class="form-check">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                           id="tag{{ $tag->id }}" class="form-check-input"
                                           @checked(in_array($tag->id, $selectedTags))>
                                    <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- قسم التقييمات و"شاهدته" --}}
                <div class="col-12"><hr class="border-secondary"><h5 class="text-warning"><i class="bi bi-star"></i> التقييمات</h5></div>

                <div class="col-md-4">
                    <label class="form-label">🟡 تقييم IMDb <small class="text-secondary">(0 - 10)</small></label>
                    <input type="number" step="0.1" min="0" max="10" name="imdb_rating" dir="ltr" lang="en" inputmode="decimal"
                           value="{{ old('imdb_rating', $title->imdb_rating) }}"
                           class="form-control bg-dark text-light border-secondary @error('imdb_rating') is-invalid @enderror">
                    @error('imdb_rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">🍅 Rotten Tomatoes <small class="text-secondary">(0 - 100%)</small></label>
                    <input type="number" min="0" max="100" name="rt_rating" dir="ltr" lang="en" inputmode="numeric"
                           value="{{ old('rt_rating', $title->rt_rating) }}"
                           class="form-control bg-dark text-light border-secondary @error('rt_rating') is-invalid @enderror">
                    @error('rt_rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">⭐ تقييمي الشخصي <small class="text-secondary">(1 - 10)</small></label>
                    <input type="number" min="1" max="10" name="personal_rating" dir="ltr" lang="en" inputmode="numeric"
                           value="{{ old('personal_rating', $title->personal_rating) }}"
                           class="form-control bg-dark text-light border-secondary @error('personal_rating') is-invalid @enderror">
                    @error('personal_rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" name="watched" value="1" id="watched" class="form-check-input"
                               @checked(old('watched', $title->watched))>
                        <label class="form-check-label" for="watched">✅ شاهدت هذا العمل (يظهر في صفحة "شاهدتها")</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" name="featured" value="1" id="featured" class="form-check-input"
                               @checked(old('featured', $title->featured))>
                        <label class="form-check-label" for="featured">⭐ إضافة إلى الترشيحات (يظهر في قسم "ترشيحات" بالرئيسية)</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">تاريخ المشاهدة <small class="text-secondary">(اختياري)</small></label>
                    <input type="date" name="watched_at"
                           value="{{ old('watched_at', $title->watched_at?->format('Y-m-d')) }}"
                           class="form-control bg-dark text-light border-secondary">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning"><i class="bi bi-check-lg"></i> {{ $editing ? 'حفظ التعديلات' : 'إضافة' }}</button>
                <a href="{{ route('admin.titles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </div>
        </form>
    </div>
@endsection
