@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Roles List</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th width="5%">SL</th>
                <th width="5%%">ROLES</th>
                <th width="10%">ROLE ID</th>
                <th width="60%">PERMISSIONS</th>
                <th width="15%">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr data-role-id="{{ $role->id }}">
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->id }}</td>
                <td>
                <div class="permission-block">
                    @foreach ($role->permissions as $permission)
                    <span class="permission">        
                        {{ $permission->name }}
                    </span>
                    @endforeach
                </div>
                </td>
                <td>
                    @if ($role->name !== 'Super Admin')
                    @if (Auth::guard('admin')->user()->can('edit.roles'))
                    <a href="{{ route('edit.roles', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endif
                    @endif
                    @if ($role->name !== 'Super Admin')
                    @if (Auth::guard('admin')->user()->can('delete.roles'))
                    <a class="btn btn-danger text-white" href="{{ route('delete.roles', $role->id) }}"
                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                    Delete
                    </a>
                    <form id="delete-form-{{ $role->id }}" action="{{ route('delete.roles', $role->id) }}" method="POST" style="display: none;">
                        @method('DELETE')
                        @csrf
                    </form>
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
