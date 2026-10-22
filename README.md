<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Cetak struk kasir

Halaman kasir membaca katalog dari tabel `products` yang sama dengan dashboard admin dan menyegarkannya setiap 5 detik. Produk yang ditambah admin langsung tersedia, perubahan nama/harga ikut memperbarui keranjang terbuka, dan produk yang dihapus atau dinonaktifkan dikeluarkan dari katalog serta keranjang kasir.

Alur **BAYAR & CETAK** adalah:

1. Hubungkan dan instal printer di Windows.
2. Masukkan barang dan pembayaran di halaman kasir, lalu tekan **BAYAR & CETAK**.
3. Server membaca ulang produk dan harga dari database, menghitung total, lalu browser membuka dialog cetak Windows. Pilih printer yang diinginkan di dialog tersebut; nama kasir, rincian barang, pembayaran, dan kembalian dicetak di struk.
4. Setelah dialog ditutup, periksa hasil cetak. Pilih **Cetak ulang** bila perlu, atau **Selesaikan transaksi** untuk membersihkan keranjang.

Dialog browser tidak membutuhkan `KASIR_PRINTER_NAME` dan dapat memilih printer Windows yang tersedia bagi browser.

**Pilihan lanjutan — pengiriman langsung ESC/POS dari server:** endpoint `/kasir/receipt/print` menggunakan Mike42 dan `WindowsPrintConnector`. Untuk menggunakannya, tambahkan printer dengan driver **Generic / Text Only**, bagikan melalui **Printer Properties > Sharing**, atur `KASIR_PRINTER_NAME` di `.env` ke nama share Windows, dan jalankan Laravel menggunakan akun yang memiliki akses ke printer. `KASIR_RECEIPT_COLUMNS=32` untuk kertas 58 mm atau `42` untuk 80 mm. Jalur langsung ini mencetak barcode Code 39 untuk nomor transaksi.
