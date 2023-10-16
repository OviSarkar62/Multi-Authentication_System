@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Transaction List</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>SL</th>
                <th>TRANSACTION ID</th>
                <th>RESTAURANT</th>
                <th>PRICE</th>
                <th>PAYMENT METHOD</th>
                <th>TRANSACTION DATE</th>
                <th>ACTIONS</th>
                </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr data-transaction-id="{{ $transaction->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->restaurant_name }}</td>
                <td>{{ $transaction->price }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ date('d-m-Y h:i:s A', strtotime($transaction->created_at . '+06:00')) }}</td>
                <td>
                    @if (Auth::guard('admin')->user()->can('edit.transaction'))
                    <a href="{{ route('edit.transaction', ['id' => $transaction->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endif
                    @if (Auth::guard('admin')->user()->can('delete.transaction'))
                    <form action="{{ route('delete.transaction', ['id' => $transaction->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
