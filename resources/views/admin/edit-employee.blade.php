@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Edit Employee</h1>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('update.employee', ['id' => $employee->id]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $employee->name }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $employee->email }}" required>
        </div>

        <div class="form-group">
            <label for="role">Assign Roles</label>
            <select name="roles[]" id="roles" class="form-control select2" multiple>
                @foreach ($roles as $role)
                {{info($role)}}
                @if ($role->name !== 'Super Admin')
                @foreach(json_decode($employee->role,true) as $em)
                {{info($em)}}
                <option value="{{ $role->name }}" {{ $em ==$role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
                @endif
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection
