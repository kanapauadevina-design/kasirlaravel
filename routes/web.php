<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/prodi', function() {
    return view('programstudi');
});

Route::get('/dosen', function () {
    $dosen = [
        ['nama' => 'Dr. Budi Santoso, M.Kom', 'jabatan' => 'Ketua Program Studi'],
        ['nama' => 'Siti Aminah, S.Kom., M.Kom', 'jabatan' => 'Dosen'],
        ['nama' => 'Andi Wijaya, S.Kom., M.T', 'jabatan' => 'Dosen'],
    ];

    return view('dosen', compact('dosen'));
});

Route::get('/kontak', function () {
    return view('kontak');
});