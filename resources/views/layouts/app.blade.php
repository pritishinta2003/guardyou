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
            --sidebar-width:           260px;
            --topbar-height:           64px;
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
           TOP NAVBAR
           ══════════════════════════════════════ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(8, 12, 20, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 900;
            transition: left 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--color-on-surface-muted);
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 6px;
            transition: background 0.15s, color 0.15s;
        }

        .topbar-menu-btn:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .topbar-menu-btn svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .topbar-page-title {
            font-family: var(--font-display);
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--color-on-surface);
            letter-spacing: -0.01em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-icon-btn {
            position: relative;
            background: none;
            border: 1px solid rgba(255,255,255,0.06);
            color: var(--color-on-surface-muted);
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .topbar-icon-btn:hover {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.12);
            color: #fff;
        }

        .topbar-icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
        }

        .notif-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--color-gold);
            border-radius: 50%;
            border: 2px solid var(--color-bg);
            box-shadow: 0 0 6px rgba(220,20,60,0.5);
        }

        /* ── PROFILE DROPDOWN ── */
        .profile-dropdown-wrapper { position: relative; }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.5rem 0.35rem 0.35rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--color-on-surface);
            text-decoration: none;
        }

        .profile-trigger:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.12);
        }

        .profile-trigger-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .profile-trigger-avatar::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: -1px;
            width: 10px;
            height: 10px;
            background: #22c55e;
            border-radius: 50%;
            border: 2px solid var(--color-bg);
        }

        .profile-trigger-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
        }

        .profile-trigger-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-trigger-role {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--color-on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .profile-trigger-chevron {
            width: 16px;
            height: 16px;
            stroke: var(--color-on-surface-variant);
            stroke-width: 2;
            fill: none;
            transition: transform 0.25s cubic-bezier(0.165, 0.84, 0.44, 1), stroke 0.2s;
            flex-shrink: 0;
        }

        .profile-dropdown-wrapper.open .profile-trigger-chevron {
            transform: rotate(180deg);
            stroke: var(--color-gold);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 260px;
            background: rgba(17, 24, 39, 0.97);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.97);
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03) inset;
            z-index: 1100;
            overflow: hidden;
        }

        .profile-dropdown-wrapper.open .profile-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-header {
            padding: 1rem 1rem 0.85rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .dropdown-header-info { min-width: 0; }

        .dropdown-header-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-header-email {
            font-size: 0.72rem;
            color: var(--color-on-surface-variant);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .dropdown-body { padding: 0.5rem; }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 9px;
            color: var(--color-on-surface-muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
            opacity: 0.65;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .dropdown-item:hover svg { opacity: 1; }

        .dropdown-item.danger { color: #f87171; }
        .dropdown-item.danger svg { opacity: 0.8; }

        .dropdown-item.danger:hover {
            background: rgba(248,113,113,0.08);
            color: #fca5a5;
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 0.35rem 0.75rem;
        }

        /* ══════════════════════════════════════
           SIDEBAR
           ══════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: rgba(8, 12, 20, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .sidebar-brand {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--color-on-surface);
            text-decoration: none;
            letter-spacing: -0.03em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
        }

        .sidebar-brand span { color: var(--color-gold); }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
            position: relative;
            background:
                radial-gradient(ellipse 90% 50% at 0% 0%, rgba(220,20,60,0.18) 0%, transparent 70%),
                radial-gradient(ellipse 70% 40% at 0% 100%, rgba(176,16,48,0.12) 0%, transparent 70%);
        }

        .sidebar-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 140px;
            background: linear-gradient(180deg, rgba(220,20,60,0.15) 0%, transparent 100%);
            pointer-events: none;
        }

        .sidebar-nav::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(0deg, rgba(220,20,60,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-gold);
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 1;
            opacity: 0.9;
        }

        .nav-section-title:first-child { margin-top: 0; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
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
            left: 0;
            top: 0.5rem;
            bottom: 0.5rem;
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

        .sidebar-footer-guest-text {
            font-size: 0.72rem;
            color: var(--color-on-surface-variant);
            margin-bottom: 0.75rem;
            text-align: center;
        }

        /* ── MAIN CONTENT AREA ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(220, 20, 60, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 80% 40%, rgba(13, 17, 23, 1) 0%, transparent 50%);
            transition: margin-left 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        main {
            flex: 1;
            padding: calc(var(--topbar-height) + 2rem) 3rem 2rem 3rem;
        }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ── BUTTONS & SHARED ── */
        .btn-primary {
            padding: 0.7rem 1.6rem;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
            color: #ffffff;
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border: none;
            cursor: pointer;
            display: inline-block;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(220,20,60,0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-gold-light) 0%, var(--color-gold) 100%);
            box-shadow: 0 6px 25px rgba(220,20,60,0.45);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            padding: 0.7rem 1.6rem;
            border-radius: 6px;
            color: var(--color-on-surface);
            font-family: var(--font-display);
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
            border-color: var(--color-gold);
            color: var(--color-gold-light);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--color-gold);
            margin-bottom: 1.25rem;
        }

        .eyebrow::before {
            content: '';
            width: 28px;
            height: 2px;
            background: var(--color-gold);
            border-radius: 1px;
        }

        /* ── FOOTER ── */
        .site-footer {
            background: #050810;
            padding: 2.5rem 3rem;
            border-top: 1px solid rgba(255,255,255,0.04);
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--color-on-surface-variant);
            font-size: 0.8rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--color-on-surface); }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--color-bg); }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-gold); }

        /* ══════════════════════════════════════
           RESPONSIVE
           ══════════════════════════════════════ */
        @media (max-width: 1024px) {
            main { padding: calc(var(--topbar-height) + 1.5rem) 2rem 2rem 2rem; }
            .site-footer { padding: 2rem; }
            .profile-trigger-info { display: none; }
            .profile-trigger { padding: 0.35rem; border-radius: 10px; }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1001;
            }

            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-wrapper { margin-left: 0; }
            .topbar { left: 0; }
            .topbar-menu-btn { display: flex; }
            .topbar-page-title { font-size: 0.85rem; }
            
            .profile-trigger-info { display: none; }
            .profile-trigger { padding: 0.3rem; border-radius: 10px; }
            .profile-trigger-avatar { width: 36px; height: 36px; border-radius: 10px; }
            .profile-trigger-chevron { display: none; }
            
            main {
                padding: calc(var(--topbar-height) + 1.25rem) 1.25rem 1.5rem 1.25rem;
            }
            
            .profile-dropdown {
                right: -8px;
                width: calc(100vw - 2rem);
                max-width: 280px;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .sidebar-footer .btn-outline,
            .sidebar-footer .btn-primary {
                display: block;
                text-align: center;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ═══════ SIDEBAR ═══════ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-brand">Guard<span>You</span></a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Main Menu</div>
            
            <a href="{{ route('dashboard')}}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Dashboard</span>
            </a>

            @auth 
                @if(auth()->user()->role === 'user')
                    <a href="{{ route('bodyguards.index') }}" class="nav-link {{ request()->routeIs('bodyguards.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Order</span>
                    </a>

                    <a href="{{ route('bodyguard.landing') }}" class="nav-link {{ request()->routeIs('bodyguard.landing') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Bergabung</span>
                    </a>

                    <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Riwayat Order</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Semua Pengguna</span>
                    </a>

                    <a href="{{ route('admin.bodyguards.index') }}" class="nav-link {{ request()->routeIs('admin.bodyguards.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                        <span>Pengajuan Guard</span>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Riwayat Tugas</span>
                    </a>
                @endif
            @endauth
        </nav>

        @guest
            <div class="sidebar-footer">
                <div class="sidebar-footer-guest-text">Masuk untuk mulai memesan</div>
                <a href="{{ route('login') }}" class="btn-outline" style="display:block; text-align:center; width:100%;">Login</a>
                <a href="{{ route('register') }}" class="btn-primary" style="display:block; text-align:center; width:100%; margin-top:0.5rem;">Sign Up</a>
            </div>
        @endguest
    </aside>

    <!-- ═══════ TOP NAVBAR ═══════ -->
    <header class="topbar" id="topbar">
        <div class="topbar-left">
            <button class="topbar-menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu" type="button">
                <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
        </div>

        <div class="topbar-right">
            @auth
                <div class="profile-dropdown-wrapper" id="profileDropdown">
                    <button class="profile-trigger" onclick="toggleProfileDropdown(event)" aria-haspopup="true" aria-expanded="false" type="button">
                        <div class="profile-trigger-avatar">
                            @if(auth()->user()->avatar && file_exists(public_path('uploads/' . auth()->user()->avatar)))
                                <img src="{{ asset('uploads/' . auth()->user()->avatar) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-full h-full object-cover squared-full">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>

                        <div class="profile-trigger-info">
                            <span class="profile-trigger-name">{{ auth()->user()->name }}</span>
                            <span class="profile-trigger-role">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>

                        <svg class="profile-trigger-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="profile-dropdown" role="menu">
                        <div class="dropdown-header">
                            <div class="dropdown-header-avatar">
                                @if(auth()->user()->photo && file_exists(public_path('uploads/' . auth()->user()->photo)))
                                    <img src="{{ asset('uploads/' . auth()->user()->photo) }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-full h-full object-cover rounded-full">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="dropdown-header-info">
                                <div class="dropdown-header-name">{{ auth()->user()->name }}</div>
                                <div class="dropdown-header-email">{{ auth()->user()->email }}</div>
                            </div>
                        </div>

                        <div class="dropdown-body">
                            @if(auth()->user()->role === 'user')
                                <a href="{{ route('user.profile') }}" class="dropdown-item" role="menuitem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Profil Saya
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                                @csrf
                                <button type="submit" class="dropdown-item danger" role="menuitem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-outline" style="padding: 0.55rem 1.2rem; font-size: 0.7rem;">Login</a>
                <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.55rem 1.2rem; font-size: 0.7rem;">Sign Up</a>
            @endauth
        </div>
    </header>

    <!-- ═══════ MAIN CONTENT ═══════ -->
    <div class="main-wrapper">
        <main>
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="footer-content">
                <div>&copy; {{ date('Y') }} GuardYou. Elite Protection.</div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar(forceClose = false) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar || !overlay) return;

            const isMobile = window.innerWidth <= 768;
            if (!isMobile) return;

            let isOpen;

            if (forceClose) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                isOpen = false;
            } else {
                isOpen = sidebar.classList.toggle('active');
                overlay.classList.toggle('active', isOpen);
            }

            if (!isOpen) {
                setTimeout(() => {
                    if (!overlay.classList.contains('active')) {
                        overlay.style.display = 'none';
                    }
                }, 300);
            } else {
                overlay.style.display = 'block';
            }
        }

        function toggleProfileDropdown(e) {
            e.stopPropagation();
            const wrapper = document.getElementById('profileDropdown');
            if (!wrapper) return;

            const trigger = wrapper.querySelector('.profile-trigger');
            const isOpen = wrapper.classList.toggle('open');
            if (trigger) {
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
        }

        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('profileDropdown');
            if (wrapper && !wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
                const trigger = wrapper.querySelector('.profile-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const wrapper = document.getElementById('profileDropdown');
                if (wrapper) {
                    wrapper.classList.remove('open');
                    const trigger = wrapper.querySelector('.profile-trigger');
                    if (trigger) trigger.setAttribute('aria-expanded', 'false');
                }

                if (window.innerWidth <= 768) {
                    toggleSidebar(true);
                }
            }
        });

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar(true);
                }
            });
        });

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar || !overlay) return;

            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                overlay.style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>