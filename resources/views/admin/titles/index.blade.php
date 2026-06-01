@extends('layouts.app')

@section('title', 'إدارة الأعمال — MJCOZ TV')

@section('content')
    <h2 class="mb-4"><i class="bi bi-film text-warning"></i> إدارة الأعمال</h2>
    @include('admin.partials.nav', ['active' => 'titles'])

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.titles.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-lg"></i> إضافة عمل جديد
        </a>
    </div>

    <div class="bg-dark-2 rounded p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>التصنيف</th>
                        <th>السنة</th>
                        <th>المراجعات</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($titles as $title)
                        <tr>
                            <td>{{ $title->name }}</td>
                            <td>
                                <span class="badge {{ $title->isMovie() ? 'bg-primary' : 'bg-success' }}">
                                    {{ $title->isMovie() ? 'فيلم' : 'مسلسل' }}
                                </span>
                            </td>
                            <td>{{ $title->genre?->name ?? '—' }}</td>
                            <td>{{ $title->release_year ?? '—' }}</td>
                            <td>{{ $title->reviews_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.titles.edit', $title) }}" class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.titles.destroy', $title) }}" class="d-inline"
                                      onsubmit="return confirm('حذف «{{ $title->name }}»؟ سيتم حذف مراجعاته أيضاً.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary">لا توجد أعمال.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">{{ $titles->links() }}</div>
@endsection
