@extends('layouts.frontend')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ Auth::user()->name }}.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
@endsection
