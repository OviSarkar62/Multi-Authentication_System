@extends('layouts.app')

@section('content')
    <div class="container mt-5">

        <div class="row justify-content-center">

            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <p>Hello, {{ auth()->user()->user_type }}-{{ auth()->user()->name }}
                </ol>
            </div>
        </div>
    </div>
@endsection
