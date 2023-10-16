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
                <th width="5%">SL</th>
                <th width="10%">NAME</th>
                <th width="10%">EMAIL</th>
                <th width="60%">ROLE</th>
                <th width="15%">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admin as $employee)
            @if ($employee->role !== 'admin')
            <tr data-admin-id="{{ $employee->id }}">
                <td>{{ $loop->index }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>
                    <div class="permission-block">
                        @php
                        $roles = json_decode($employee->role, true);
                        $count = is_array($roles) ? count($roles) : 0;
                        @endphp
                        @for ($i = 0; $i < $count; $i++) 
                        <span class="permission">
                            {{ $roles[$i] }}
                        </span>
                        @endfor
                    </div>
                </td>
                <td>
                    @if (!in_array('Super Admin', $roles))
                        <a href="{{ route('edit.employee', ['id' => $employee->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm" onclick="deleteEmployee({{ $employee->id }}, '{{ $employee->role }}')">Delete</button>
                    @endif
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function deleteEmployee(employeeId, employeeRole) {
        // Check if the employee is an admin, and if so, prevent deletion
        if (employeeRole === 'admin') {
            alert('Admins cannot be deleted.');
            return;
        }

        if (confirm('Are you sure you want to delete this employee?')) {
            const url = `/employee/${employeeId}`;
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
                        const row = document.querySelector(`tr[data-admin-id="${employeeId}"]`);
                        if (row) {
                            row.remove();
                            alert('Employee deleted successfully.');
                        }
                    } else {
                        alert('Error deleting the employee.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error deleting the employee.');
                });
        }
    }
</script>


@endsection