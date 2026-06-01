<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'CineReview'))</title>

    {{-- Bootstrap 5 RTL (مستضاف محلياً) --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fonts/tajawal.css') }}" rel="stylesheet">

    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #0f1117; color: #e9ecef; }
        a { text-decoration: none; }
        .navbar-brand { font-weight: 700; }
        .bg-dark-2 { background-color: #1a1d27; }
        .card-title-poster {
            position: relative;
            overflow: hidden;
            border-radius: .75rem;
            background-color: #1a1d27;
            transition: transform .2s ease, box-shadow .2s ease;
            height: 100%;
        }
        .card-title-poster:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,.5); }
        .card-title-poster img { width: 100%; aspect-ratio: 2/3; object-fit: cover; }
        .poster-badge { position: absolute; top: .5rem; inset-inline-start: .5rem; }
        .rating-stars { color: #ffc107; }
        /* إدخال التقييم بالنجوم (RTL): النجوم بترتيب 5..1 فالـ siblings اللاحقة تُضاء */
        .star-input { display: inline-flex; flex-direction: row; }
        .star-input input { display: none; }
        .star-input label { color: #555; cursor: pointer; padding: 0 .1rem; transition: color .15s; }
        .star-input label:hover,
        .star-input label:hover ~ label,
        .star-input input:checked ~ label { color: #ffc107; }
        .hero {
            background: linear-gradient(135deg, #1f2433 0%, #0f1117 100%);
            border-radius: 1rem;
        }
        footer { border-top: 1px solid #2a2e3a; }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- شريط التنقل --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-2 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-film text-warning"></i> CineReview
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('movies.index') }}"><i class="bi bi-camera-reels"></i> أفلام</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('series.index') }}"><i class="bi bi-tv"></i> مسلسلات</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('watched.index') }}"><i class="bi bi-eye"></i> شاهدتها</a></li>
                </ul>
                <form class="d-flex me-lg-3" role="search" action="{{ url('/titles') }}" method="GET">
                    <input class="form-control form-control-sm bg-dark text-light border-secondary" type="search"
                           name="q" placeholder="ابحث عن عمل..." value="{{ request('q') }}">
                </form>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">دخول</a></li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm mt-1" href="{{ route('register') }}">إنشاء حساب</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}" title="الإشعارات">
                                <i class="bi bi-bell"></i>
                                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                                @if ($unread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unread > 9 ? '9+' : $unread }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person"></i> ملفي الشخصي</a></li>
                                <li><a class="dropdown-item" href="{{ route('watchlist.index') }}"><i class="bi bi-bookmark-heart"></i> أريد مشاهدته</a></li>
                                @if (auth()->user()->canManageContent())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> لوحة التحكم</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item"><i class="bi bi-box-arrow-right"></i> تسجيل الخروج</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- رسائل النظام --}}
    @if (session('status'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- المحتوى --}}
    <main class="container my-4 flex-grow-1">
        @yield('content')
    </main>

    {{-- التذييل --}}
    <footer class="bg-dark-2 py-4 mt-auto">
        <div class="container text-center text-secondary small">
            <i class="bi bi-film text-warning"></i> CineReview &copy; {{ date('Y') }} — موقع مراجعة الأفلام والمسلسلات
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
