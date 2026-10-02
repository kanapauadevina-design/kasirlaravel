<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Kasir - VioraMart</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8f9ff;
            color: #222;
        }

        /* NAVBAR ORBIT */
        .navbar {
            width: 100%;
            background: white;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-size: 15px;
            font-weight: 500;
        }

        .nav-menu a:hover {
            color: #4f46e5;
        }

        .nav-button {
            background: #4f46e5;
            color: white !important;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .hamburger {
            display: none;
            font-size: 25px;
            cursor: pointer;
        }

        /* CONTAINER */
        .container {
            width: 90%;
            max-width: 1400px;
            margin: 40px auto;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            font-size: 32px;
            color: #222;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #777;
        }

        /* HEADER TRANSAKSI */
        .transaction-header {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .store-name {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }

        .transaction-info {
            text-align: right;
            color: #666;
        }

        /* LAYOUT */
        .pos-container {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        }

        .card h3 {
            margin-bottom: 18px;
            color: #333;
        }

        /* SEARCH */
        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        .search-box button {
            padding: 13px 20px;
            border: none;
            border-radius: 8px;
            background: #4f46e5;
            color: white;
            cursor: pointer;
        }

        #productResults {
            margin-top: 10px;
        }

        .product-result {
            padding: 12px;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            background: #fafafa;
        }

        .product-result:hover {
            background: #eef2ff;
        }

        /* FORM */
        select,
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        /* TABLE */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f4f4ff;
            padding: 13px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        .empty-cart {
            text-align: center;
            color: #999;
            padding: 30px;
        }

        /* SUMMARY */
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            color: #555;
        }

        .summary-row.total {
            border-top: 2px solid #eee;
            margin-top: 10px;
            padding-top: 18px;
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }

        /* PAYMENT */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin: 15px 0;
        }

        .payment-methods button {
            padding: 12px 5px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .payment-methods button.active {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .change-box {
            background: #eef2ff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        /* ACTION */
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .actions button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-hold {
            background: #f59e0b;
            color: white;
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
        }

        .btn-pay {
            background: #4f46e5;
            color: white;
        }

        .qty-btn {
            padding: 5px 9px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: #4f46e5;
            color: white;
        }

        .remove-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            background: #ef4444;
            color: white;
            cursor: pointer;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .pos-container {
                grid-template-columns: 1fr;
            }

            .transaction-info {
                text-align: left;
            }
        }

        @media (max-width: 700px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 20px;
            }

            .nav-menu.active {
                display: flex;
            }

            .hamburger {
                display: block;
            }

            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .actions {
                flex-direction: column;
            }
        }

        :root {
            color-scheme: light;
            --ink: #182c27;
            --muted: #68756f;
            --line: #dce5df;
            --paper: #f2f5f0;
            --surface: #ffffff;
            --green: #176b52;
            --green-dark: #104b3b;
            --lime: #d8ed8d;
            --orange: #e77d48;
            --danger: #bd5144;
        }

        body {
            min-height: 100vh;
            background-color: var(--paper);
            background-image: linear-gradient(rgba(23, 107, 82, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(23, 107, 82, .035) 1px, transparent 1px);
            background-size: 28px 28px;
            color: var(--ink);
            font-family: "Aptos", "Segoe UI", sans-serif;
        }

        .navbar {
            min-height: 72px;
            padding: 14px max(5%, calc((100% - 1400px) / 2));
            background: var(--green-dark);
            box-shadow: 0 5px 18px rgba(16, 75, 59, .16);
        }

        .logo {
            color: var(--lime);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            letter-spacing: 1px;
        }

        .nav-menu {
            gap: 22px;
        }

        .nav-menu a {
            color: rgba(255, 255, 255, .76);
            font-size: 14px;
        }

        .nav-menu a:hover,
        .nav-menu a:focus-visible {
            color: var(--lime);
        }

        .nav-menu .nav-button {
            padding: 10px 17px;
            border: 1px solid rgba(216, 237, 141, .55);
            border-radius: 5px;
            background: var(--lime);
            color: var(--green-dark) !important;
        }

        .hamburger {
            color: white;
        }

        .container {
            width: min(92%, 1400px);
            margin: 34px auto 48px;
        }

        .page-title {
            margin-bottom: 21px;
        }

        .page-title h1 {
            margin-bottom: 6px;
            color: var(--ink);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 600;
        }

        .page-title p,
        .transaction-info,
        footer {
            color: var(--muted);
        }

        .transaction-header {
            align-items: center;
            padding: 18px 22px;
            border: 1px solid var(--line);
            border-left: 5px solid var(--orange);
            border-radius: 6px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgba(24, 44, 39, .045);
        }

        .store-name {
            color: var(--green);
            font-size: 18px;
            letter-spacing: .4px;
        }

        .transaction-header small {
            display: inline-block;
            margin-top: 5px;
            color: var(--muted);
        }

        .transaction-info {
            line-height: 1.8;
        }

        .transaction-info strong {
            color: var(--ink);
            font-variant-numeric: tabular-nums;
        }

        .pos-container {
            grid-template-columns: minmax(0, 1.65fr) minmax(320px, .85fr);
            align-items: start;
            gap: 20px;
        }

        .pos-container > div:last-child {
            position: sticky;
            top: 92px;
        }

        .card {
            margin-bottom: 16px;
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgba(24, 44, 39, .045);
        }

        .card h3 {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 16px;
            color: var(--ink);
            font-size: 16px;
            font-weight: 700;
        }

        .search-box input,
        select,
        input[type="number"],
        input[type="text"] {
            min-height: 46px;
            border: 1px solid #cbd7d0;
            border-radius: 5px;
            background: #fff;
            color: var(--ink);
            font: inherit;
        }

        input::placeholder {
            color: #8b9690;
        }

        input:focus,
        select:focus {
            border-color: var(--green);
            outline: 3px solid rgba(23, 107, 82, .13);
        }

        .search-box button,
        .btn-pay {
            border-radius: 5px;
            background: var(--green);
            color: white;
            font-weight: 700;
            transition: background-color .18s ease, transform .18s ease;
        }

        .search-box button:hover,
        .btn-pay:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        .product-result {
            border-color: var(--line);
            border-radius: 5px;
            background: #f8faf7;
        }

        .product-result:hover {
            border-color: #9bbbaa;
            background: #edf5ef;
        }

        .table-wrapper {
            border: 1px solid var(--line);
            border-radius: 5px;
        }

        table {
            min-width: 640px;
        }

        th {
            background: #edf3ee;
            color: #53645b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        td {
            color: #34443d;
            font-size: 14px;
        }

        td small {
            color: var(--muted);
        }

        .empty-cart {
            padding: 38px 20px;
            color: var(--muted);
        }

        #itemCount {
            display: inline-grid;
            min-width: 26px;
            min-height: 26px;
            place-items: center;
            margin-left: 5px;
            border-radius: 4px;
            background: #edf3ee;
            color: var(--green-dark);
        }

        .summary-row {
            align-items: center;
            gap: 14px;
            padding: 11px 0;
            border-bottom: 1px solid #edf1ed;
            color: var(--muted);
            font-size: 14px;
        }

        .summary-row strong {
            color: var(--ink);
            font-variant-numeric: tabular-nums;
        }

        .summary-row input {
            width: 80px !important;
            min-height: 38px;
            padding: 7px 10px;
            text-align: right;
        }

        .summary-row.total {
            margin-top: 8px;
            padding-top: 17px;
            border-top: 2px solid var(--green);
            border-bottom: 0;
            color: var(--green-dark);
            font-size: 18px;
        }

        .summary-row.total strong {
            color: var(--green-dark);
            font-size: 22px;
        }

        .payment-methods button {
            min-height: 42px;
            border: 1px solid var(--line);
            border-radius: 5px;
            color: var(--ink);
            transition: border-color .18s ease, background-color .18s ease;
        }

        .payment-methods button:hover {
            border-color: var(--green);
            background: #edf5ef;
        }

        .payment-methods button.active {
            border-color: var(--green);
            background: var(--green);
            color: white;
        }

        .change-box {
            border: 1px solid #d2e5d7;
            border-radius: 5px;
            background: #eff6ed;
            color: var(--green-dark);
        }

        .change-box span {
            font-size: 20px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .actions {
            margin-top: 0;
        }

        .actions button {
            min-height: 46px;
            border-radius: 5px;
            transition: filter .18s ease, transform .18s ease;
        }

        .actions button:hover {
            filter: brightness(.94);
            transform: translateY(-1px);
        }

        .btn-hold {
            background: #d9a33d;
            color: #263029;
        }

        .btn-cancel,
        .remove-btn {
            background: var(--danger);
            color: white;
        }

        .qty-btn {
            min-width: 30px;
            min-height: 30px;
            border-radius: 4px;
            background: var(--green);
        }

        .remove-btn {
            border-radius: 4px;
        }

        footer {
            padding: 22px;
            border-top: 1px solid var(--line);
            font-size: 13px;
        }

        button:focus-visible,
        a:focus-visible {
            outline: 3px solid rgba(231, 125, 72, .55);
            outline-offset: 2px;
        }

        @media (max-width: 900px) {
            .pos-container {
                grid-template-columns: minmax(0, 1fr);
            }

            .pos-container > div:last-child {
                position: static;
            }
        }

        @media (max-width: 700px) {
            .navbar {
                padding: 14px 5%;
            }

            .nav-menu {
                top: 72px;
                align-items: stretch;
                gap: 14px;
                border-top: 1px solid rgba(255, 255, 255, .12);
                background: var(--green-dark);
            }

            .nav-menu a {
                text-align: center;
            }

            .container {
                width: 92%;
                margin-top: 25px;
            }

            .transaction-header {
                padding: 16px;
            }

            .transaction-info {
                text-align: left;
            }

            .card {
                padding: 16px;
            }

            .search-box {
                flex-direction: column;
            }

            .search-box button {
                min-height: 44px;
            }

            .payment-methods {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .actions {
                flex-direction: column;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<!-- halaman kasir -->
<header class="navbar">

    <div class="logo">VioraMart <span style="font-size:13px; font-weight:500; color:#68756f;">| KASIR</span></div>

    <nav class="nav-menu" id="navMenu">
        <a href="/">Home</a>
        <span style="color:var(--muted); font-size:14px;">{{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-button" style="border:0; cursor:pointer; font:inherit;">Logout</button>
        </form>
    </nav>

    <div class="hamburger" onclick="toggleMenu()">
        ☰
    </div>

</header>


<main class="container">

    <!-- JUDUL -->
    <div class="page-title">
        <h1>Transaksi Pembayaran</h1>
    </div>


    <!-- HEADER TRANSAKSI -->
    <div class="transaction-header">

        <div>
            <div class="store-name">
                TOKO RETAIL MAKMUR
            </div>

            <small>
                Sistem Pembayaran Kasir
            </small>
        </div>

        <div class="transaction-info">

            <div>
                No. Transaksi:
                <strong>TRX-{{ now()->format('Ymd-His') }}</strong>
            </div>
            <div>{{ now()->format('d M Y, H:i') }}</div>

        </div>

    </div>


    <div class="pos-container">

        <!-- BAGIAN KIRI -->
        <div>

            <!-- CARI PRODUK -->
            <div class="card">

                <h3>🔎 Cari Produk</h3>

                <div class="search-box">

                    <input
                        type="text"
                        id="searchProduct"
                        placeholder="Cari nama, kode atau barcode..."
                        oninput="searchProduct(this.value)"
                    >

                    <button type="button" onclick="searchProduct(document.getElementById('searchProduct').value)">
                        Cari
                    </button>

                </div>

                <div id="productResults"></div>

            </div>


            <!-- PELANGGAN -->
            <div class="card">

                <h3>👤 Data Pelanggan</h3>

                <select id="customer">
                    <option value="">Pilih Pelanggan</option>
                    <option value="Umum">Umum</option>
                    <option value="Member">Member</option>
                </select>

                <br><br>

                <input
                    type="text"
                    id="memberPhone"
                    placeholder="Nomor HP / Member"
                >

            </div>


            <!-- KERANJANG -->
            <div class="card">

                <h3>🛒 Keranjang Belanja</h3>

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="cartBody">

                            <tr>
                                <td colspan="6" class="empty-cart">
                                    Keranjang masih kosong
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                <br>

                <strong>
                    Jumlah Item:
                    <span id="itemCount">0</span>
                </strong>

            </div>

        </div>


        <!-- BAGIAN KANAN -->
        <div>

            <!-- RINGKASAN -->
            <div class="card">

                <h3>💰 Ringkasan Pembayaran</h3>

                <div class="summary-row">
                    <span>Total Qty</span>
                    <strong id="totalQty">0</strong>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong id="subtotal">Rp 0</strong>
                </div>

                <div class="summary-row">
                    <span>Diskon (%)</span>

                    <input
                        type="number"
                        id="discountPercent"
                        value="0"
                        min="0"
                        max="100"
                        style="width:80px;"
                    >
                </div>

                <div class="summary-row">
                    <span>Diskon</span>
                    <strong id="discountAmount">Rp 0</strong>
                </div>

                <div class="summary-row">
                    <span>Pajak</span>
                    <strong id="tax">Rp 0</strong>
                </div>

                <div class="summary-row">
                    <span>Biaya Lain</span>
                    <strong id="otherFee">Rp 0</strong>
                </div>

                <div class="summary-row total">
                    <span>Total</span>
                    <strong id="grandTotal">Rp 0</strong>
                </div>

            </div>


            <!-- PEMBAYARAN -->
            <div class="card">

                <h3>💳 Pembayaran</h3>

                <input
                    type="number"
                    id="payment"
                    placeholder="Masukkan jumlah pembayaran"
                >


                <div class="payment-methods">

                    <button onclick="selectPayment(this, 'Tunai')">
                        Tunai
                    </button>

                    <button onclick="selectPayment(this, 'QRIS')">
                        QRIS
                    </button>

                    <button onclick="selectPayment(this, 'Debit')">
                        Debit
                    </button>

                    <button onclick="selectPayment(this, 'Kredit')">
                        Kredit
                    </button>

                    <button onclick="selectPayment(this, 'E-Wallet')">
                        E-Wallet
                    </button>

                    <button onclick="selectPayment(this, 'Transfer')">
                        Transfer
                    </button>

                </div>


                <div class="change-box">

                    <strong>Status Pembayaran</strong>

                    <br><br>

                    <span id="change">
                        Rp 0
                    </span>

                </div>

            </div>


            <!-- AKSI -->
            <div class="card">

                <div class="actions">

                    <button
                        class="btn-hold"
                        onclick="holdTransaction()">
                        Tahan
                    </button>

                    <button
                        class="btn-cancel"
                        onclick="cancelTransaction()">
                        Batal
                    </button>

                    <button
                        class="btn-pay"
                        onclick="processPayment()">
                        BAYAR & CETAK
                    </button>

                </div>

            </div>

        </div>

    </div>

</main>


<footer>
    © 2026 Toko Retail Makmur — Sistem Kasir
</footer>


<script>

    function toggleMenu() {
        document
            .getElementById("navMenu")
            .classList.toggle("active");
    }

</script>


<script src="{{ asset('assets/js/chasier.js') }}"></script>

</body>
</html>