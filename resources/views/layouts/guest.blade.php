<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'GuardYou — Platform jasa bodyguard profesional terverifikasi. Pesan perlindungan personal kapan saja.')">
    <title>@yield('title', 'GuardYou') | Elite Protection</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-bg:                #080c14;
            --color-surface:           #0d1117;
            --color-surface-container: #111827;
            --color-surface-high:      #161b22;
            --color-border:            rgba(255,255,255,0.08);
            --color-on-surface:        #ffffff;
            --color-on-surface-muted:  #94a3b8;
            --color-on-surface-variant:#64748b;
            --color-gold:              #DC143C;
            --color-gold-light:        #FF2D55;
            --color-gold-dark:         #B01030;
            --color-gold-bg:           rgba(220,20,60,0.08);
            --topbar-height:           64px;
            --sidebar-width:           280px;
            --font-display: 'Outfit', sans-serif;
            --font-body:    'Inter', sans-serif;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background-color: var(--color-bg);
            color: var(--color-on-surface);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            letter-spacing: -0.02em;
            font-weight: 800;
            line-height: 1.1;
        }

        /* ══════════════════════════════════════
           TOPBAR
           ══════════════════════════════════════ */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-height);
            background: rgba(8, 12, 20, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 900;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .topbar-menu-btn {
            background: none;
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--color-on-surface-muted);
            cursor: pointer;
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .topbar-menu-btn:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.15);
            color: #fff;
        }
        .topbar-menu-btn svg {
            width: 20px; height: 20px;
            stroke: currentColor; stroke-width: 2; fill: none;
        }

        .topbar-brand {
            font-family: var(--font-display);
            font-size: 1.25rem; font-weight: 900;
            color: var(--color-on-surface);
            text-decoration: none; letter-spacing: -0.03em;
            text-transform: uppercase;
        }
        .topbar-brand span { color: var(--color-gold); }

        .topbar-page-title {
            font-family: var(--font-display);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--color-on-surface-muted);
            letter-spacing: -0.01em;
            padding-left: 0.85rem;
            border-left: 1px solid rgba(255,255,255,0.08);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ══════════════════════════════════════
           SIDEBAR — SLIDE-OUT
           ══════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: rgba(8, 12, 20, 0.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            z-index: 1100;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: none;
        }
        .sidebar.active {
            transform: translateX(0);
            box-shadow: 20px 0 60px rgba(0,0,0,0.5);
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .sidebar-brand {
            font-family: var(--font-display);
            font-size: 1.3rem; font-weight: 900;
            color: var(--color-on-surface);
            text-decoration: none; letter-spacing: -0.03em;
            text-transform: uppercase;
        }
        .sidebar-brand span { color: var(--color-gold); }

        .sidebar-close {
            background: none;
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--color-on-surface-variant);
            cursor: pointer;
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .sidebar-close:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
            border-color: rgba(255,255,255,0.15);
        }
        .sidebar-close svg {
            width: 18px; height: 18px;
            stroke: currentColor; stroke-width: 2; fill: none;
        }

        /* ── Nav area dengan gradasi merah ── */
        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
            position: relative;
            /* Gradient merah sebagai background nav */
            background:
                radial-gradient(ellipse 90% 50% at 0% 0%, rgba(220,20,60,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 70% 40% at 0% 100%, rgba(176,16,48,0.08) 0%, transparent 70%);
        }

        /* Garis aksen merah di atas nav */
        .sidebar-nav::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 120px;
            background: linear-gradient(180deg, rgba(220,20,60,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        /* Garis aksen merah di bawah nav */
        .sidebar-nav::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 80px;
            background: linear-gradient(0deg, rgba(220,20,60,0.06) 0%, transparent 100%);
            pointer-events: none;
        }

        .nav-section-title {
            font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--color-gold);
            padding: 0 1.5rem; margin-bottom: 0.75rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 1;
            opacity: 0.85;
        }
        .nav-section-title:first-child { margin-top: 0; }

        .nav-link {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.8rem 1.5rem;
            /* Kontras tinggi — putih lembut, bukan abu-abu */
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 0.88rem; font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }
        .nav-link svg {
            width: 20px; height: 20px;
            stroke: currentColor; stroke-width: 1.5; fill: none;
            opacity: 0.6;
            transition: all 0.2s;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.06);
            color: #ffffff;
        }
        .nav-link:hover svg { opacity: 1; }
        .nav-link.active {
            color: #ffffff;
            background: rgba(220, 20, 60, 0.15);
            box-shadow: inset 0 0 20px rgba(220,20,60,0.06);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0.5rem; bottom: 0.5rem;
            width: 3px;
            background: linear-gradient(180deg, var(--color-gold-light), var(--color-gold-dark));
            border-radius: 0 2px 2px 0;
            box-shadow: 0 0 8px rgba(220,20,60,0.5);
        }
        .nav-link.active svg {
            color: var(--color-gold-light);
            opacity: 1;
            filter: drop-shadow(0 0 4px rgba(220,20,60,0.4));
        }

        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            position: relative;
            z-index: 1;
        }
        .sidebar-footer-text {
            font-size: 0.72rem;
            color: var(--color-on-surface-muted);
            margin-bottom: 0.75rem;
            text-align: center;
            line-height: 1.5;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ══════════════════════════════════════
           MAIN CONTENT
           ══════════════════════════════════════ */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image:
                radial-gradient(circle at 50% 0%, rgba(220, 20, 60, 0.06) 0%, transparent 50%);
        }

        main {
            flex: 1;
            padding-top: var(--topbar-height);
        }

        /* ══════════════════════════════════════
           BUTTONS
           ══════════════════════════════════════ */
        .btn-primary {
            padding: 0.7rem 1.6rem;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--color-gold), var(--color-gold-dark));
            color: #fff;
            font-family: var(--font-display);
            font-size: 0.75rem; font-weight: 800;
            text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em;
            border: none; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(220,20,60,0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-gold-light), var(--color-gold));
            box-shadow: 0 6px 25px rgba(220,20,60,0.45);
            transform: translateY(-2px);
        }

        .btn-outline {
            padding: 0.7rem 1.6rem;
            border-radius: 6px;
            color: var(--color-on-surface);
            font-family: var(--font-display);
            font-size: 0.75rem; font-weight: 700;
            text-decoration: none; text-transform: uppercase; letter-spacing: 0.08em;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
            border-color: var(--color-gold);
            color: var(--color-gold-light);
        }

        .btn-nav { padding: 0.55rem 1.3rem; font-size: 0.72rem; }

        .btn-lg {
            padding: 0.9rem 2rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 0.75rem;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.2em; color: var(--color-gold);
            margin-bottom: 1.25rem;
        }
        .eyebrow::before {
            content: ''; width: 28px; height: 2px;
            background: var(--color-gold); border-radius: 1px;
        }

        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.5rem);
            color: #fff;
        }
        .section-title span { color: var(--color-gold); }

        /* ══════════════════════════════════════
           FOOTER
           ══════════════════════════════════════ */
        .guest-footer {
            background: #050810;
            border-top: 1px solid rgba(255,255,255,0.04);
            margin-top: auto;
        }
        .footer-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 3rem 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
        }
        .footer-brand-desc {
            font-size: 0.82rem;
            color: var(--color-on-surface-variant);
            line-height: 1.7;
            max-width: 280px;
            margin-top: 0.75rem;
        }
        .footer-col-title {
            font-family: var(--font-display);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: var(--color-on-surface);
            margin-bottom: 1.25rem;
        }
        .footer-links {
            display: flex; flex-direction: column; gap: 0.65rem;
        }
        .footer-links a {
            color: var(--color-on-surface-variant);
            text-decoration: none; font-size: 0.82rem;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--color-on-surface); }
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 3rem;
            border-top: 1px solid rgba(255,255,255,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--color-on-surface-variant);
            font-size: 0.78rem;
        }
        .footer-bottom-links { display: flex; gap: 1.5rem; }
        .footer-bottom-links a {
            color: inherit; text-decoration: none; transition: color 0.2s;
        }
        .footer-bottom-links a:hover { color: var(--color-on-surface); }

        /* ══════════════════════════════════════
           SCROLLBAR
           ══════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--color-bg); }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-gold); }

        /* ══════════════════════════════════════
           RESPONSIVE
           ══════════════════════════════════════ */
        @media (max-width: 1024px) {
            .topbar-page-title { display: none; }
            .footer-main { padding: 2.5rem 2rem 1.5rem; grid-template-columns: 1fr 1fr; gap: 2rem; }
            .footer-brand-block { grid-column: 1 / -1; }
            .footer-bottom { padding: 1.25rem 2rem; }
        }

        @media (max-width: 768px) {
            .topbar { padding: 0 1.25rem; }
            .topbar-brand { font-size: 1.1rem; }
            .btn-nav { padding: 0.5rem 1rem; font-size: 0.68rem; }
            .footer-main { padding: 2rem 1.25rem 1.5rem; grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; gap: 0.75rem; text-align: center; padding: 1.25rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-brand">Guard<span>You</span></a>
            <button class="sidebar-close" onclick="toggleSidebar()" aria-label="Tutup menu">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu</div>
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('bodyguard.landing') }}" class="nav-link">
                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <span>Bergabung</span>
            </a>

            <div class="nav-section-title">Informasi</div>
            <a href="{{ url('/') }}#features" class="nav-link">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span>Tentang Kami</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <p class="sidebar-footer-text">Buat akun untuk mulai memesan layanan perlindungan.</p>
            <a href="{{ route('login') }}" class="btn-outline" style="display: block; text-align: center; width: 100%;">Login</a>
            <a href="{{ route('register') }}" class="btn-primary" style="display: block; text-align: center; width: 100%; margin-top: 0.5rem;">Sign Up</a>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="topbar" id="topbar">
        <div class="topbar-left">
            <button class="topbar-menu-btn" onclick="toggleSidebar()" aria-label="Buka menu">
                <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            <a href="{{ url('/') }}" class="topbar-brand">Guard<span>You</span></a>
            <span class="topbar-page-title">@yield('page_title', '')</span>
        </div>
        <div class="topbar-right">
            <a href="{{ route('login') }}" class="btn-outline btn-nav">Login</a>
            <a href="{{ route('register') }}" class="btn-primary btn-nav">Sign Up</a>
        </div>
    </header>

    <!-- Main -->
    <div class="main-wrapper">
        <main>
            @yield('content')
        </main>

        <footer class="guest-footer">
            <div class="footer-main">
                <div class="footer-brand-block">
                    <a href="{{ url('/') }}" class="topbar-brand" style="font-size: 1.2rem;">Guard<span>You</span></a>
                    <p class="footer-brand-desc">Platform jasa bodyguard profesional terverifikasi. Pesan perlindungan personal kapan saja, di mana saja.</p>
                </div>
                <div>
                    <div class="footer-col-title">Layanan</div>
                    <div class="footer-links">
                        <a href="{{ route('bodyguards.index') }}">Temukan Bodyguard</a>
                        <a href="{{ route('bodyguard.landing') }}">Bergabung</a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Perusahaan</div>
                    <div class="footer-links">
                        <a href="{{ url('/') }}#features">Tentang Kami</a>
                        <a href="{{ url('/') }}#cta">Kontak</a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Legal</div>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} GuardYou. Elite Protection.</div>
                <div class="footer-bottom-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="{{ url('/') }}#cta">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = sidebar.classList.toggle('active');
            overlay.classList.toggle('active', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('active')) toggleSidebar();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash) {
                const target = document.querySelector(window.location.hash);
                if (target) {
                    setTimeout(() => {
                        const top = target.getBoundingClientRect().top + window.scrollY - 64;
                        window.scrollTo({ top: top, behavior: 'smooth' });
                    }, 200);
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>