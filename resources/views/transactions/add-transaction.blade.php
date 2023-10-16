@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Add Transaction</h1>
            <h3 class="text-center">Add a transaction for customer</h3>
        </div>

        <div class="col-md-6 offset-md-3 mt-5 mb-5">
            <div class="card" id="card">
                <div class="card-header">Add Transaction</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store.transaction') }}">
                        @csrf

                        <div class="form-group">
                            <label for="restaurant_name">Restaurant</label>
                            <input type="text" id="restaurant_name" name="restaurant_name"
                                class="form-control @error('restaurant_name') is-invalid @enderror"
                                required>
                            @error('restaurant_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" id="price" name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                required>
                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Payment Status</label>
                            <div class="form-check">
                                <input type="radio" id="cod" name="status" value="cod" class="form-check-input">
                                <label for="cod" class="form-check-label">Cash On Delivery</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="partial" name="status" value="partial" class="form-check-input">
                                <label for="partial" class="form-check-label">Partial Payment</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="digital" name="status" value="digital" class="form-check-input">
                                <label for="digital" class="form-check-label">Digital Payment</label>
                            </div>
                        </div>

                        <div class="form-group mb-5 mt-3">
                            <button type="submit" class="btn btn-info btn-block" id="btnRegister">Add</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="message" class="mt-2"></div>
        </div>
    </div>
</div>
@endsection
