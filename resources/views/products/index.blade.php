@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Product List</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>SL</th>
                <th>NAME</th>
                <th>DESCRIPTION</th>
                <th>PRICE</th>
                <th>CATEGORY</th>
                <th>CREATED AT</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr data-product-id="{{ $product->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_description }}</td>
                <td>{{ $product->product_price }}</td>
                <td>{{ $product->product_category }}</td>
                <td>{{ date('d-m-Y h:i:s A', strtotime($product->created_at . '+06:00')) }}</td>
                <td>
                    @if (Auth::guard('admin')->user()->can('products.edit'))
                    {{-- <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                    @endif
                    @if (Auth::guard('admin')->user()->can('products.destroy'))
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct({{ $product->id }})">Delete</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            const url = `/products/${productId}`;
            const csrfToken = '{{ csrf_token() }}';

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                        if (row) {
                            row.remove();
                            alert('Product deleted successfully.');
                        }
                    } else {
                        alert('Error deleting the product.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error deleting the product.');
                });
        }
    }
</script>
@endsection
