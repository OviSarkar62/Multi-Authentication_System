@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Add Employee</h1>
            <h3 class="text-center">Create an account for an employee</h3>
        </div>

        <div class="col-md-6 offset-md-3 mt-5 mb-5">
            <div class="card" id="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store.employee') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Assign Roles</label>
                            <select name="roles[]" id="roles" class="form-control select2" multiple>
                                @foreach ($roles as $role)
                                @if ($role->name !== 'Super Admin')
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-5 mt-3">
                            <button type="submit" class="btn btn-info btn-block" id="btnRegister">Register</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="message" class="mt-2"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>

@endsection
