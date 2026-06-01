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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/titles') }}">الأفلام والمسلسلات</a></li>
                </ul>
                <form class="d-flex" role="search" action="{{ url('/titles') }}" method="GET">
                    <input class="form-control form-control-sm bg-dark text-light border-secondary" type="search"
                           name="q" placeholder="ابحث عن عمل..." value="{{ request('q') }}">
                </form>
            </div>
        </div>
    </nav>

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
