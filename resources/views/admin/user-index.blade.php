@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Employee List</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>SL</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>USER TYPE</th>
                <th>CREATED AT</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr data-admin-id="{{ $user->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->user_type }}</td>
                <td>{{ date('d-m-Y h:i:s A', strtotime($user->created_at . '+06:00')) }}</td>
                <td>
                <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            const url = `/user/${userId}`;
            const csrfToken = '{{ csrf_token() }}';

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                    if (row) {
                        row.remove();
                        alert('User deleted successfully.');
                    }
                } else {
                    alert('Error deleting the user.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Error deleting the user.');
            });
        }
    }
</script>

@endsection
