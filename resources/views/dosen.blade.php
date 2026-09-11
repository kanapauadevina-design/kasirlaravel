@extends('layouts.app')

@section('title', 'Dosen')

@section('content')

<div class="hero">
    <h1>Dosen Program Studi Teknologi Informasi</h1>
    <p>Daftar dosen Program Studi Teknologi Informasi.</p>
</div>

<div class="cards">

    @foreach($dosen as $d)
        <div class="card">
            <h2>{{ $d['nama'] }}</h2>
            <p><strong>Jabatan:</strong> {{ $d['jabatan'] }}</p>
        </div>
    @endforeach

</div>

@endsection