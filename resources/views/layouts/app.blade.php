<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'MJCOZ TV'))</title>

    {{-- Bootstrap 5 RTL (مستضاف محلياً) --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fonts/tajawal.css') }}" rel="stylesheet">

    <style>
        :root {
            --bg: #0d0f15;
            --surface: #161a24;
            --surface-2: #1c2130;
            --accent: #ffc107;
            --accent-2: #e23744;
            --text: #e9ecef;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Tajawal', sans-serif;
            color: var(--text);
            background-color: var(--bg);
            /* خلفية سينمائية ناعمة مريحة للعين */
            background-image:
                radial-gradient(1100px 500px at 85% -10%, rgba(226,55,68,.10), transparent 60%),
                radial-gradient(1000px 500px at 0% 0%, rgba(255,193,7,.08), transparent 55%);
            background-attachment: fixed;
            line-height: 1.7;
        }
        a { text-decoration: none; transition: color .2s ease; }
        a:hover { color: var(--accent); }

        /* انتقالات لطيفة عامة */
        .btn, .nav-link, .badge, .card-title-poster, .form-control, .form-select { transition: all .2s ease; }
        .btn:hover { transform: translateY(-2px); }
        .btn-warning { box-shadow: 0 4px 14px rgba(255,193,7,.25); }

        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .navbar-brand .bi { filter: drop-shadow(0 0 6px rgba(255,193,7,.55)); }

        /* شريط تنقّل زجاجي */
        .navbar.bg-dark-2 {
            background-color: rgba(18,21,30,.82) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .nav-link:hover { color: var(--accent) !important; }

        .bg-dark-2 { background-color: var(--surface); }

        /* كروت الأعمال — تفاعلية */
        .card-title-poster {
            position: relative;
            overflow: hidden;
            border-radius: .9rem;
            background-color: var(--surface);
            border: 1px solid rgba(255,255,255,.05);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
            height: 100%;
        }
        .card-title-poster:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 34px rgba(0,0,0,.55);
            border-color: rgba(255,193,7,.45);
        }
        .card-title-poster img {
            width: 100%; aspect-ratio: 2/3; object-fit: cover;
            transition: transform .4s ease;
        }
        .card-title-poster:hover img { transform: scale(1.07); }
        .poster-badge { position: absolute; top: .5rem; inset-inline-start: .5rem; z-index: 2; }

        /* غلاف الصورة + أيقونة التشغيل التفاعلية */
        .poster-thumb { position: relative; overflow: hidden; }
        .poster-thumb::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(13,15,21,.85), transparent 45%);
            opacity: .85; transition: opacity .25s ease;
        }
        .poster-play {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 3rem; opacity: 0; transform: scale(.7);
            transition: opacity .25s ease, transform .25s ease; z-index: 1;
            text-shadow: 0 4px 16px rgba(0,0,0,.6);
        }
        .card-title-poster:hover .poster-play { opacity: 1; transform: scale(1); color: var(--accent); }

        .rating-stars { color: var(--accent); }

        /* إدخال التقييم بالنجوم (RTL) */
        .star-input { display: inline-flex; flex-direction: row; }
        .star-input input { display: none; }
        .star-input label { color: #555; cursor: pointer; padding: 0 .1rem; transition: color .15s, transform .15s; }
        .star-input label:hover { transform: scale(1.2); }
        .star-input label:hover,
        .star-input label:hover ~ label,
        .star-input input:checked ~ label { color: var(--accent); }

        /* البانر السينمائي */
        .hero {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(226,55,68,.18) 0%, rgba(28,33,48,.9) 45%, #0d0f15 100%);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 1.25rem;
            box-shadow: inset 0 0 80px rgba(0,0,0,.4);
        }

        /* حقول الإدخال المركّزة */
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(255,193,7,.15);
            background-color: #11141c;
        }

        footer { border-top: 1px solid rgba(255,255,255,.06); }

        /* شريط تمرير مريح */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #2c3344; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- شريط التنقل --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-2 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-film text-warning"></i> MJCOZ TV
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
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear"></i> تعديل الملف الشخصي</a></li>
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
            <i class="bi bi-film text-warning"></i> MJCOZ TV &copy; {{ date('Y') }} — موقع مراجعة الأفلام والمسلسلات
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
