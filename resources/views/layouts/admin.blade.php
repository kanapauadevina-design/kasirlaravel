<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | VioraMart Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-sidebar: #831843;
            --admin-accent: #db2777;
            --admin-background: #fff7fb;
            --bs-primary: #db2777;
            --bs-primary-rgb: 219, 39, 119;
            --bs-primary-text-emphasis: #831843;
            --bs-primary-bg-subtle: #fce7f3;
            --bs-primary-border-subtle: #f9a8d4;
        }

        body {
            min-height: 100vh;
            background: var(--admin-background);
            color: #1e293b;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
            width: 250px;
            padding: 1.5rem 1rem;
            background: var(--admin-sidebar);
            color: #fff;
        }

        .btn-primary {
            --bs-btn-bg: var(--admin-accent);
            --bs-btn-border-color: var(--admin-accent);
            --bs-btn-hover-bg: #be185d;
            --bs-btn-hover-border-color: #be185d;
            --bs-btn-active-bg: #9d174d;
            --bs-btn-active-border-color: #9d174d;
        }

        .btn-outline-primary {
            --bs-btn-color: #be185d;
            --bs-btn-border-color: #ec4899;
            --bs-btn-hover-bg: var(--admin-accent);
            --bs-btn-hover-border-color: var(--admin-accent);
            --bs-btn-active-bg: #9d174d;
            --bs-btn-active-border-color: #9d174d;
        }

        .admin-content .card {
            border: 1px solid #fce7f3 !important;
            border-radius: 14px;
        }

        .admin-content .card-header {
            border-radius: 14px 14px 0 0;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 0 .75rem 1.75rem;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 750;
            text-decoration: none;
        }

        .admin-brand-mark {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            border-radius: 12px;
            background: #ec4899;
            font-weight: 800;
        }

        .admin-nav-label {
            margin: .5rem .75rem .65rem;
            color: #f9a8d4;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin: .25rem 0;
            padding: .8rem .85rem;
            border-radius: 10px;
            color: #fce7f3;
            text-decoration: none;
            transition: background .15s ease, color .15s ease;
        }

        .admin-nav-link:hover,
        .admin-nav-link.active {
            background: rgba(249, 168, 212, .2);
            color: #fff;
        }

        .admin-nav-icon {
            width: 1.35rem;
            text-align: center;
        }

        .admin-main {
            min-height: 100vh;
            margin-left: 250px;
        }

        .admin-topbar {
            display: flex;
            min-height: 76px;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 2rem;
            border-bottom: 1px solid #fce7f3;
            background: #fff;
        }

        .admin-content {
            max-width: 1500px;
            margin: 0 auto;
            padding: 2rem;
        }

        .admin-footer {
            padding: 1rem 2rem;
            color: #94a3b8;
            font-size: .85rem;
        }

        @media (max-width: 767.98px) {
            .admin-sidebar {
                position: static;
                width: 100%;
                padding: .75rem 1rem;
            }

            .admin-brand {
                padding: .25rem .5rem .75rem;
            }

            .admin-nav-label {
                display: none;
            }

            .admin-nav {
                display: flex;
                gap: .35rem;
                overflow-x: auto;
            }

            .admin-nav-link {
                flex: 0 0 auto;
                margin: 0;
                padding: .65rem .75rem;
                font-size: .9rem;
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-topbar {
                min-height: 64px;
                padding: .85rem 1rem;
            }

            .admin-content {
                padding: 1.25rem 1rem;
            }
        }
    </style>
</head>
<body>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <span class="admin-brand-mark">V</span>
            <span>VioraMart <small class="d-block fw-normal text-white-50">ADMIN PANEL</small></span>
        </a>

        <p class="admin-nav-label">Menu utama</p>
        <nav class="admin-nav" aria-label="Navigasi admin">
            <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="admin-nav-icon" aria-hidden="true">▦</span> Dashboard
            </a>
            <a class="admin-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <span class="admin-nav-icon" aria-hidden="true">▣</span> Produk
            </a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <div class="text-secondary small">Panel Admin</div>
                <strong>@yield('page-heading', 'Dashboard')</strong>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-secondary small">Administrator</div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>

        <footer class="admin-footer">
            © {{ now()->year }} VioraMart Admin
        </footer>
    </div>
</body>
</html>
