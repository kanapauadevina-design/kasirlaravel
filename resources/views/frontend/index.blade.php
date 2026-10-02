<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orbit - Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f7f7ff;
            color: #333;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            height: 64px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            border-bottom: 1px solid #eee;
        }

        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
            color: #222;
        }

        .navbar .menu {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .navbar a {
            text-decoration: none;
            color: #555;
            font-size: 14px;
        }

        .navbar .login-btn {
            background: #1769e8;
            color: white;
            padding: 10px 20px;
            border-radius: 7px;
        }

        /* =========================
           HERO
        ========================= */

        .hero {
            width: 100%;
            min-height: 300px;
            background: linear-gradient(
                135deg,
                #f5f7ff,
                #ffffff
            );

            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .hero-content {
            width: 900px;
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        .hero-text {
            flex: 1;
        }

        .hero-text h1 {
            font-size: 34px;
            line-height: 1.2;
            margin-bottom: 15px;
            color: #222;
        }

        .hero-text p {
            color: #777;
            line-height: 1.6;
            font-size: 14px;
        }

        .hero-image {
            flex: 1;
            height: 210px;
            border-radius: 15px;

            background:
                linear-gradient(
                    rgba(20, 100, 220, .15),
                    rgba(20, 100, 220, .05)
                ),
                #e9efff;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #1769e8;
            font-weight: bold;
            font-size: 20px;
        }

        /* =========================
           LOGIN AREA
        ========================= */

        .login-section {
            min-height: 480px;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 50px 20px;
        }

        /* LOGIN CARD */

        .login-card {
            width: 370px;
            max-width: 100%;

            background: #ffffff;

            padding: 28px 23px;

            border-radius: 13px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.08);

            text-align: center;
        }

        .login-card h2 {
            font-size: 23px;
            margin-bottom: 7px;
            color: #222;
        }

        .login-card .subtitle {
            color: #999;
            font-size: 12px;
            margin-bottom: 22px;
        }

        /* FORM */

        .form-group {
            text-align: left;
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;

            font-size: 12px;
            color: #555;

            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            height: 40px;

            border: 1px solid #e2e5ed;
            border-radius: 6px;

            background: #eef2fc;

            padding: 0 12px;

            font-size: 13px;

            outline: none;

            transition: .2s;
        }

        .form-group input:focus {
            border-color: #1769e8;

            background: #ffffff;

            box-shadow:
                0 0 0 2px rgba(23, 105, 232, .1);
        }

        /* LOGIN BUTTON */

        .btn-login {
            width: 100%;
            height: 40px;

            margin-top: 2px;

            border: none;
            border-radius: 6px;

            background: #1769e8;

            color: white;

            font-size: 12px;
            font-weight: bold;

            cursor: pointer;

            transition: .2s;
        }

        .btn-login:hover {
            background: #0d5bd1;
        }

        /* BACK LINK */

        .back-link {
            display: inline-block;

            margin-top: 18px;

            font-size: 11px;

            color: #1594b8;

            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* =========================
           BOTTOM AD
        ========================= */

        .bottom-ad {
            width: 100%;
            min-height: 110px;

            background: white;

            border-top: 1px solid #eee;

            display: flex;
            align-items: center;

            padding: 12px 22px;

            gap: 15px;
        }

        .ad-image {
            width: 80px;
            height: 80px;

            border-radius: 5px;

            background: linear-gradient(
                135deg,
                #111,
                #444
            );

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            font-size: 10px;
            text-align: center;
        }

        .ad-content {
            flex: 1;
        }

        .ad-content h3 {
            font-size: 16px;
            color: #222;

            margin-bottom: 5px;
        }

        .ad-content p {
            font-size: 12px;
            color: #999;
        }

        .install-btn {
            border: none;

            background: #087bdc;

            color: white;

            padding: 11px 22px;

            border-radius: 20px;

            font-size: 12px;

            cursor: pointer;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            .navbar {
                height: 55px;
                padding: 0 18px;
            }

            .navbar .logo {
                font-size: 18px;
            }

            .navbar .menu {
                gap: 10px;
            }

            .navbar .menu a:not(.login-btn) {
                display: none;
            }

            .hero {
                min-height: 230px;
                padding: 25px 18px;
            }

            .hero-content {
                flex-direction: column-reverse;
                gap: 20px;
            }

            .hero-text {
                text-align: center;
            }

            .hero-text h1 {
                font-size: 25px;
            }

            .hero-image {
                width: 100%;
                height: 130px;
            }

            .login-section {
                min-height: 450px;
                padding: 35px 18px;
            }

            .login-card {
                width: 100%;
                max-width: 370px;
                padding: 27px 22px;
            }

            .bottom-ad {
                min-height: 100px;
                padding: 10px 15px;
            }

            .ad-image {
                width: 65px;
                height: 65px;
            }

            .ad-content h3 {
                font-size: 14px;
            }

            .ad-content p {
                font-size: 10px;
            }

            .install-btn {
                padding: 9px 16px;
            }
        }
    </style>
</head>

<body>

    <!-- =========================
         NAVBAR
    ========================== -->

    <nav class="navbar">

        <div class="logo">
            Orbit
        </div>

        <div class="menu">
            <a href="#">Home</a>
            <a href="#">Tentang</a>
            <a href="#">Kontak</a>

            <a href="#" class="login-btn">
                Login
            </a>
        </div>

    </nav>


    <!-- =========================
         HERO
    ========================== -->

    <section class="hero">

        <div class="hero-content">

            <div class="hero-text">

                <h1>
                    Innovating for Your Success
                    Through Technology
                </h1>

                <p>
                    Solusi teknologi inovatif untuk membantu
                    meningkatkan produktivitas dan kesuksesan
                    bisnis Anda.
                </p>

            </div>

            <div class="hero-image">
                 TECHNOLOGY
            </div>

        </div>

    </section>


    <!-- =========================
         LOGIN
    ========================== -->

    <section class="login-section">

        <div class="login-card">

            <h2>
                Orbit
            </h2>

            <p class="subtitle">
                Silakan login ke akun Anda
            </p>


            <form id="loginForm">

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        placeholder="Masukkan email"
                        value="seller@pasarkita.test"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        placeholder="Masukkan password"
                        value="12345678"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn-login"
                >
                    Login
                </button>

            </form>


            <a href="#" class="back-link">
                ← Kembali ke Orbit
            </a>

        </div>

    </section>


    <!-- =========================
         BOTTOM AD
    ========================== -->

    <div class="bottom-ad">

        <div class="ad-image">
            PineDrama
        </div>

        <div class="ad-content">

            <h3>
                PineDrama
            </h3>

            <p>
                Seudang seri Tonton gratis
            </p>

        </div>

        <button
            class="install-btn"
            onclick="installApp()"
        >
            INSTALL
        </button>

    </div>


    <!-- =========================
         JAVASCRIPT
    ========================== -->

    <script>

        const loginForm =
            document.getElementById("loginForm");


        loginForm.addEventListener(
            "submit",
            function(event) {

                event.preventDefault();

                const email =
                    document.getElementById("email").value;

                const password =
                    document.getElementById("password").value;


                if (email === "" || password === "") {

                    alert("Email dan password wajib diisi!");

                    return;
                }


                // Contoh login sederhana
                alert(
                    "Login berhasil!\n\nEmail: "
                    + email
                );

            }
        );


        function installApp() {

            alert(
                "Menuju halaman download aplikasi..."
            );

        }

    </script>

</body>
</html>
