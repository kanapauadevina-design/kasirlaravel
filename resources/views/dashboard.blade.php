@extends('layouts.frontend')

@section('title', 'Dashboard')

@section('content')
    <div class="card dashboard-card p-4 p-md-5">
        <span class="badge rounded-pill align-self-start mb-3" style="background:#fce7f3;color:#be185d;">Area pengguna</span>
        <h1 class="h2 fw-bold">Dashboard</h1>
        <p class="text-primary fw-semibold">Selamat datang, {{ Auth::user()->name }}.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>
    </div>
@endsection
