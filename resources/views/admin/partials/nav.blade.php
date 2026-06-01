{{-- شريط تنقّل لوحة الأدمن. متغير اختياري: $active --}}
@php $active = $active ?? ''; @endphp
<div class="bg-dark-2 p-2 rounded mb-4">
    <ul class="nav nav-pills gap-1">
        <li class="nav-item">
            <a class="nav-link {{ $active === 'dashboard' ? 'active' : 'text-light' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> لوحة التحكم
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $active === 'titles' ? 'active' : 'text-light' }}" href="{{ route('admin.titles.index') }}">
                <i class="bi bi-film"></i> الأعمال
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $active === 'genres' ? 'active' : 'text-light' }}" href="{{ route('admin.genres.index') }}">
                <i class="bi bi-tags"></i> التصنيفات
            </a>
        </li>
        @if (auth()->user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ $active === 'users' ? 'active' : 'text-light' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> المستخدمون
                </a>
            </li>
        @endif
    </ul>
</div>
