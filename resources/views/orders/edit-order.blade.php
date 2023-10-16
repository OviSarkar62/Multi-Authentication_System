@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Edit Order</h1>
            <h3 class="text-center">Edit the order for Customer</h3>
        </div>

        <div class="col-md-6 offset-md-3 mt-5 mb-5">
            <div class="card" id="card">
                <div class="card-header">Edit Order</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('update.order', $order->id) }}">
                        @csrf
                        @method('PUT') {{-- Use PUT method to update the order --}}

                        <div class="form-group">
                            <label for="name">Customer Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ $order->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="restaurant_name">Restaurant</label>
                            <input type="text" id="restaurant_name" name="restaurant_name"
                                class="form-control" value="{{ $order->restaurant_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" id="price" name="price"
                                class="form-control" value="{{ $order->price }}" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Order Status</label>
                            <div class="form-check">
                                <input type="radio" id="processing" name="status" value="processing"
                                    class="form-check-input" {{ $order->status == 'processing' ? 'checked' : '' }}>
                                <label for="processing" class="form-check-label">Processing</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="pending" name="status" value="pending"
                                    class="form-check-input" {{ $order->status == 'pending' ? 'checked' : '' }}>
                                <label for="pending" class="form-check-label">Pending</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="delivered" name="status" value="delivered"
                                    class="form-check-input" {{ $order->status == 'delivered' ? 'checked' : '' }}>
                                <label for="delivered" class="form-check-label">Delivered</label>
                            </div>
                        </div>

                        <div class="form-group mb-5 mt-3">
                            <button type="submit" class="btn btn-info btn-block" id="btnUpdate">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
