<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'MJCOZ TV'))</title>

    {{-- تطبيق الثيم المحفوظ قبل الرسم لتفادي الوميض --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    {{-- Bootstrap 5 RTL (مستضاف محلياً) --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fonts/tajawal.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fonts/orbitron.css') }}" rel="stylesheet">

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

        /* ترقيم الصفحات (Bootstrap) على ثيم الموقع */
        .pagination { --bs-pagination-bg: var(--surface); --bs-pagination-color: var(--text);
            --bs-pagination-border-color: rgba(255,255,255,.1);
            --bs-pagination-hover-bg: var(--surface-2); --bs-pagination-hover-color: var(--accent);
            --bs-pagination-hover-border-color: rgba(255,193,7,.4);
            --bs-pagination-focus-bg: var(--surface-2); --bs-pagination-focus-color: var(--accent);
            --bs-pagination-active-bg: var(--accent); --bs-pagination-active-border-color: var(--accent);
            --bs-pagination-active-color: #1a1d27;
            --bs-pagination-disabled-bg: var(--surface); --bs-pagination-disabled-color: #6b7280;
            --bs-pagination-disabled-border-color: rgba(255,255,255,.08);
            gap: .25rem; flex-wrap: wrap; }
        .pagination .page-link { border-radius: .5rem; min-width: 42px; text-align: center; transition: all .15s; }

        /* زر العودة للأعلى */
        #toTop {
            position: fixed; inset-block-end: 24px; inset-inline-start: 24px; z-index: 1030;
            width: 46px; height: 46px; border-radius: 50%; border: none;
            background: var(--accent); color: #1a1d27; font-size: 1.3rem;
            box-shadow: 0 6px 18px rgba(0,0,0,.4);
            opacity: 0; visibility: hidden; transform: translateY(10px);
            transition: opacity .25s, transform .25s, visibility .25s;
        }
        #toTop.show { opacity: 1; visibility: visible; transform: translateY(0); }
        #toTop:hover { transform: translateY(-3px); }

        /* ===== الوضع النهاري ===== */
        [data-theme="light"] {
            --bg: #f3f5fa; --surface: #ffffff; --surface-2: #eef1f7; --text: #1b2030;
        }
        [data-theme="light"] body {
            color: var(--text);
            background-image:
                radial-gradient(1100px 500px at 85% -10%, rgba(226,55,68,.08), transparent 60%),
                radial-gradient(1000px 500px at 0% 0%, rgba(255,193,7,.10), transparent 55%);
        }
        [data-theme="light"] .bg-dark-2 { background-color: var(--surface); }
        [data-theme="light"] .navbar.bg-dark-2 {
            background-color: rgba(255,255,255,.85) !important;
            border-bottom-color: rgba(0,0,0,.08);
        }
        [data-theme="light"] .navbar-brand,
        [data-theme="light"] .navbar .nav-link { color: #2a2f3a !important; }
        [data-theme="light"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(40,45,55,0.85)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        [data-theme="light"] .text-light { color: #1b2030 !important; }
        [data-theme="light"] .text-secondary { color: #5b6573 !important; }
        [data-theme="light"] .bg-dark { background-color: #eef1f7 !important; color: #1b2030 !important; }
        [data-theme="light"] .form-control, [data-theme="light"] .form-select {
            background-color: #fff !important; color: #1b2030 !important; border-color: #cfd6e4 !important;
        }
        [data-theme="light"] .card-title-poster { background-color: #fff; border-color: rgba(0,0,0,.08); }
        [data-theme="light"] .dropdown-menu-dark {
            --bs-dropdown-bg: #fff; --bs-dropdown-color: #1b2030; --bs-dropdown-link-color: #1b2030;
            --bs-dropdown-link-hover-bg: #f0f2f7; border: 1px solid rgba(0,0,0,.1);
        }
        [data-theme="light"] .table-dark {
            --bs-table-bg: #fff; --bs-table-color: #1b2030; --bs-table-border-color: #e3e7ef;
            --bs-table-hover-bg: #f3f5fa; --bs-table-hover-color: #1b2030;
        }
        [data-theme="light"] .poster-thumb::after {
            background: linear-gradient(to top, rgba(255,255,255,.9), transparent 45%);
        }
        [data-theme="light"] #toTop { color: #1a1d27; }

        /* ============ RETRO / SYNTHWAVE THEME ============ */
        :root{
            --bg:#0d0221; --surface:#1b1036; --surface-2:#2a1a4a;
            --accent:#ff2e97; --accent-2:#00e5ff; --text:#f3e9ff;
        }
        body{
            background-color:var(--bg);
            background-image:linear-gradient(180deg,#3a0ca3 0%,#240046 38%,#0d0221 72%);
            background-attachment:fixed;
        }
        /* شبكة الأفق الريترو */
        body::before{
            content:""; position:fixed; left:0; right:0; bottom:0; height:40vh; z-index:-1; pointer-events:none;
            background-image:
                repeating-linear-gradient(to right, rgba(0,229,255,.12) 0 1px, transparent 1px 7%),
                repeating-linear-gradient(to top, rgba(255,46,151,.12) 0 1px, transparent 1px 14%);
            transform:perspective(420px) rotateX(62deg); transform-origin:bottom; opacity:.28;
            -webkit-mask-image:linear-gradient(to top,#000,transparent);
            mask-image:linear-gradient(to top,#000,transparent);
        }
        /* نجوم الخلفية المتوهّجة المتحركة */
        .stars{
            position:fixed; inset:0; z-index:-1; pointer-events:none;
            background-repeat:repeat; background-size:210px 210px;
            background-image:
                radial-gradient(2.4px 2.4px at 20% 30%, #ffffff, transparent),
                radial-gradient(2px 2px at 70% 18%, #9fe6ff, transparent),
                radial-gradient(2px 2px at 42% 72%, #ff8fd0, transparent),
                radial-gradient(2.6px 2.6px at 86% 60%, #ffffff, transparent),
                radial-gradient(2px 2px at 14% 86%, #7fdcff, transparent),
                radial-gradient(2.2px 2.2px at 55% 45%, #ffffff, transparent),
                radial-gradient(2px 2px at 92% 33%, #ffc0e6, transparent),
                radial-gradient(2.2px 2.2px at 32% 14%, #ffffff, transparent);
            filter:drop-shadow(0 0 4px rgba(255,255,255,.85)) drop-shadow(0 0 6px rgba(0,229,255,.6));
            animation:starDrift 90s linear infinite, twinkle 3.5s ease-in-out infinite alternate;
        }
        .stars::after{
            content:""; position:absolute; inset:0; opacity:.7;
            background-repeat:repeat; background-size:150px 150px;
            background-image:
                radial-gradient(1.6px 1.6px at 12% 22%, #ffffff, transparent),
                radial-gradient(1.6px 1.6px at 60% 50%, #bfefff, transparent),
                radial-gradient(1.6px 1.6px at 80% 12%, #ffffff, transparent),
                radial-gradient(1.6px 1.6px at 35% 88%, #ffb8e0, transparent),
                radial-gradient(1.6px 1.6px at 95% 75%, #ffffff, transparent);
            animation:starDrift 60s linear infinite reverse, twinkle 5s ease-in-out infinite alternate;
        }
        @keyframes starDrift{ from{ background-position:0 0; } to{ background-position:-140px -900px; } }
        @keyframes twinkle{ 0%{ opacity:.4; } 100%{ opacity:1; } }
        @media (prefers-reduced-motion: reduce){ .stars,.stars::after{ animation:none; } }

        h1,h2,h3,.navbar-brand{
            font-family:'Orbitron','Tajawal',sans-serif;
            text-shadow:0 0 6px rgba(255,46,151,.22); letter-spacing:.5px;
        }
        .navbar-brand{ color:#fff!important; font-weight:700; }
        .navbar-brand .bi{ color:var(--accent-2)!important; filter:drop-shadow(0 0 5px rgba(0,229,255,.55)); }

        .text-warning{ color:var(--accent-2)!important; text-shadow:0 0 5px rgba(0,229,255,.22); }
        .rating-stars,.star-input input:checked ~ label,.star-input label:hover,.star-input label:hover ~ label{ color:var(--accent)!important; }

        .navbar.bg-dark-2{ background-color:rgba(13,2,33,.78)!important; border-bottom:1px solid rgba(255,46,151,.28); }
        .bg-dark-2{ background-color:var(--surface)!important; }
        .nav-link:hover{ color:var(--accent-2)!important; }

        .btn-warning{ background:linear-gradient(135deg,#ff2e97,#ff6ec7)!important; border:1px solid #ff2e97!important; color:#1a0030!important; font-weight:700; box-shadow:0 3px 12px rgba(255,46,151,.28)!important; }
        .btn-warning:hover{ box-shadow:0 4px 18px rgba(255,46,151,.45)!important; }
        .btn-outline-warning{ color:var(--accent-2)!important; border-color:var(--accent-2)!important; }
        .btn-outline-warning:hover{ background:var(--accent-2)!important; color:#04121a!important; }
        .btn-outline-secondary:hover{ border-color:var(--accent-2)!important; color:var(--accent-2)!important; }

        .card-title-poster{ background-color:var(--surface)!important; border:1px solid rgba(0,229,255,.14)!important; }
        .card-title-poster:hover{ border-color:var(--accent)!important; box-shadow:0 8px 22px rgba(255,46,151,.25)!important; }
        .card-title-poster:hover .poster-play{ color:var(--accent-2)!important; text-shadow:0 0 12px rgba(0,229,255,.6); }

        .hero{
            background:linear-gradient(135deg,#3a1668 0%,#241050 52%,#160a36 100%)!important;
            border:1px solid rgba(0,229,255,.4)!important;
            box-shadow:0 18px 46px rgba(0,0,0,.6), 0 0 26px rgba(255,46,151,.2), inset 0 0 50px rgba(0,0,0,.3)!important;
        }
        /* خطوط VHS خفيفة على البانر */
        .hero::after{
            content:""; position:absolute; inset:0; z-index:0; pointer-events:none; border-radius:inherit;
            background:repeating-linear-gradient(to bottom, rgba(255,255,255,.035) 0 1px, transparent 1px 3px);
            opacity:.6; animation:vhs 6s linear infinite;
        }
        .hero > *{ position:relative; z-index:1; }
        @keyframes vhs{ from{ background-position:0 0; } to{ background-position:0 3px; } }

        /* توهّج ناعم عند المرور */
        .nav-link{ transition:color .2s ease, text-shadow .2s ease; }
        .nav-link:hover{ text-shadow:0 0 8px rgba(0,229,255,.55); }
        .btn{ transition:transform .2s ease, box-shadow .2s ease, filter .2s ease; }
        .badge{ transition:filter .2s ease; }
        .card-title-poster:hover h6{ text-shadow:0 0 8px rgba(255,46,151,.4); }
        a:hover .bi{ filter:drop-shadow(0 0 5px rgba(0,229,255,.5)); }

        .form-control:focus,.form-select:focus{ border-color:var(--accent)!important; box-shadow:0 0 0 .2rem rgba(255,46,151,.2)!important; background-color:#160a2e!important; }
        .bg-dark{ background-color:#160a2e!important; }

        .badge.bg-primary{ background:#7b2ff7!important; }
        .badge.bg-success{ background:#00b4a0!important; }
        .badge.bg-info{ background:var(--accent-2)!important; color:#04121a!important; }

        ::-webkit-scrollbar-thumb{ background:#5a189a; }
        ::-webkit-scrollbar-thumb:hover{ background:var(--accent); }
        .pagination{ --bs-pagination-bg:var(--surface); --bs-pagination-color:var(--text);
            --bs-pagination-hover-color:var(--accent-2);
            --bs-pagination-active-bg:var(--accent); --bs-pagination-active-border-color:var(--accent); --bs-pagination-active-color:#1a0030; }
        #toTop{ background:linear-gradient(135deg,#ff2e97,#00e5ff)!important; color:#0d0221!important; box-shadow:0 0 18px rgba(255,46,151,.6); }
        footer{ border-top:1px solid rgba(0,229,255,.25); }

        /* الوضع النهاري: ريترو دافئ (70s) */
        [data-theme="light"]{ --bg:#fff3da; --surface:#fffaf0; --surface-2:#f6ead2; --accent:#ff5c8a; --accent-2:#0a9aab; --text:#3a2a1a; }
        [data-theme="light"] body{ background-image:linear-gradient(180deg,#ffe2c4,#fff3da 60%)!important; }
        [data-theme="light"] body::before{ opacity:.2; }
        [data-theme="light"] h1,[data-theme="light"] h2,[data-theme="light"] h3,[data-theme="light"] .navbar-brand{ text-shadow:none; }
        [data-theme="light"] .navbar-brand{ color:#7a3b12!important; }
        [data-theme="light"] .text-warning{ color:#e0007a!important; text-shadow:none; }
        [data-theme="light"] .navbar.bg-dark-2{ background-color:rgba(255,250,240,.92)!important; border-bottom-color:rgba(224,0,122,.3); box-shadow:none; }
        [data-theme="light"] .card-title-poster{ background:#fff!important; border-color:rgba(224,0,122,.2)!important; }
        [data-theme="light"] .btn-warning{ color:#fff!important; }
        [data-theme="light"] .stars{ display:none; }
        [data-theme="light"] .bg-dark{ background-color:#fff!important; }
        [data-theme="light"] .form-control:focus,[data-theme="light"] .form-select:focus{ background-color:#fff!important; }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- نجوم الخلفية المتوهّجة --}}
    <div class="stars" aria-hidden="true"></div>

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

                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <button id="themeToggle" class="btn btn-sm btn-outline-secondary border-0 nav-link" title="تبديل الوضع">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
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

    {{-- زر العودة للأعلى --}}
    <button id="toTop" title="للأعلى" aria-label="للأعلى"><i class="bi bi-arrow-up"></i></button>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        // تبديل الوضع الليلي/النهاري
        (function () {
            var root = document.documentElement;
            var btn = document.getElementById('themeToggle');
            function syncIcon() {
                var dark = root.getAttribute('data-theme') !== 'light';
                btn.innerHTML = dark ? '<i class="bi bi-moon-stars"></i>' : '<i class="bi bi-sun"></i>';
            }
            syncIcon();
            btn.addEventListener('click', function () {
                var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
                root.setAttribute('data-theme', next);
                try { localStorage.setItem('theme', next); } catch (e) {}
                syncIcon();
            });

            // زر العودة للأعلى
            var toTop = document.getElementById('toTop');
            window.addEventListener('scroll', function () {
                toTop.classList.toggle('show', window.scrollY > 400);
            });
            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
