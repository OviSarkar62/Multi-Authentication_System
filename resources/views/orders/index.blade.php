@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Order List</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>SL</th>
                <th>ORDER ID</th>
                <th>CUSTOMER INFORMATION</th>
                <th>RESTAURANT</th>
                <th>PRICE</th>
                <th>ORDER DATE</th>
                <th>ORDER STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr data-order-id="{{ $order->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->restaurant_name }}</td>
                <td>{{ $order->price }}</td>
                <td>{{ date('d-m-Y h:i:s A', strtotime($order->created_at . '+06:00')) }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    @if (Auth::guard('admin')->user()->can('edit.order'))
                    <a href="{{ route('edit.order', ['id' => $order->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endif
                    @if (Auth::guard('admin')->user()->can('delete.order'))
                    <form action="{{ route('delete.order', ['id' => $order->id]) }}" method="POST" style="display: inline;">
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
