<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - Program Studi</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff5f9;
            color: #3f2b3a;
        }

        nav {
            background: linear-gradient(110deg, #9d174d, #ec4899);
            padding: 18px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h2 {
            color: white;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .hero {
            background: #1e88e5;
            color: white;
            padding: 60px 30px;
            border-radius: 15px;
            text-align: center;
        }

        .card {
            background: white;
            padding: 25px;
            margin-top: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #123c69;
            color: white;
        }

        footer {
            margin-top: 50px;
            background: #123c69;
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav>
        <h2>Program Studi</h2>

        <div>
            <a href="/">Home</a>
            <a href="/tentang">Tentang</a>
            <a href="/prodi">program studi</a>
            <a href="/dosen">Dosen</a>
            <a href="/kontak">Kontak</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <footer>
        © 2026 Program Studi Teknologi Informasi
    </footer>

</body>
</html>