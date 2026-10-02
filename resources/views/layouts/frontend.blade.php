<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Website Saya')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --pink: #db2777;
            --pink-dark: #9d174d;
            --pink-light: #fce7f3;
            --pink-pale: #fff7fb;
        }

        body {
            min-height: 100vh;
            background: var(--pink-pale);
            color: #3f2433;
        }

        .site-navbar {
            background: linear-gradient(110deg, var(--pink-dark), var(--pink));
            box-shadow: 0 4px 18px rgba(157, 23, 77, .18);
        }

        .site-navbar .navbar-brand {
            letter-spacing: .02em;
        }

        .site-login-button {
            border: 1px solid rgba(255, 255, 255, .75);
            color: var(--pink-dark);
            font-weight: 600;
        }

        .site-login-button:hover {
            border-color: #fff;
            background: var(--pink-light);
            color: var(--pink-dark);
        }

        .site-footer {
            background: var(--pink-dark);
        }

        .home-hero {
            padding: clamp(2.5rem, 6vw, 5rem) 1.5rem;
            border: 1px solid #f9c6dc;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #fce7f3, #fff 72%);
            box-shadow: 0 18px 45px rgba(190, 24, 93, .09);
        }

        .home-hero h1 {
            color: var(--pink-dark);
            letter-spacing: -.035em;
        }

        .home-hero .lead {
            max-width: 650px;
            margin-inline: auto;
            color: #78566a;
        }

        .btn-pink {
            border-color: var(--pink);
            background: var(--pink);
            color: #fff;
            font-weight: 600;
        }

        .btn-pink:hover,
        .btn-pink:focus {
            border-color: var(--pink-dark);
            background: var(--pink-dark);
            color: #fff;
        }

        .home-feature-card {
            height: 100%;
            border: 1px solid #f7d4e3;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(157, 23, 77, .06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .home-feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(157, 23, 77, .12);
        }

        .home-feature-card h2 {
            color: var(--pink-dark);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark site-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                VioraMart
            </a>

            <a href="{{ route('login') }}" class="btn btn-light site-login-button">
                Login
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer text-white text-center py-3 mt-5">
        © {{ now()->year }} VioraMart
    </footer>

</body>
</html>