<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multi-Auth System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<style>
    .form-check-label {
        text-transform: capitalize;
    }
    .permission-block {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .permission {
        margin: 5px; /* Adjust the margin size to create space around each block */
        padding: 5px;
        background-color: #007bff;
        color: #fff;
        border-radius: 5px;
    }
</style>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
    <nav class="navbar navbar-expand-lg bg-dark shadow-lg" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @if(Auth::guard('web')->check()||Auth::guard('admin')->check())
                    <li class="nav-item">
                        <a class="nav-link" id="logout" href="#">Logout</a>
                    </li>
                    <form id="form-logout" action="{{ route('logout') }}" method="post">@csrf</form>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.user') }}">User Registration</a>
                    </li>
                    @endif
                    @if(Auth::guard('admin')->check())

                    @if ($usr->can('dashboardadmin'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboardadmin') }}">Dashboard</a>
                    </li>
                    @endif
                    @if ($usr->can('create.roles'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.roles') }}">Add Role</a>
                    </li>
                    @endif
                    @if ($usr->can('admin.roles'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.roles') }}">Roles</a>
                    </li>
                    @endif
                    @if ($usr->can('create.employee'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.employee') }}">Add Employee</a>
                    </li>
                    @endif
                    @if ($usr->can('employee.index'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employee.index') }}">Employee List</a>
                    </li>
                    @endif
                    @if ($usr->can('create.order'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.order') }}">Add Order</a>
                    </li>
                    @endif
                    @if ($usr->can('order.index'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order.index') }}">Order List</a>
                    </li>
                    @endif
                    @if ($usr->can('create.transaction'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.transaction') }}">Add Transaction</a>
                    </li>
                    @endif
                    @if ($usr->can('transaction.index'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transaction.index') }}">Transaction List</a>
                    </li>
                    @endif
                    @if ($usr->can('products.create'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.create') }}">Add Product</a>
                    </li>
                    @endif
                    @if ($usr->can('products.index'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Product List</a>
                    </li>
                    @endif
                    @endif

                    @if(!$adminRegistered )
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('create.admin') }}">Admin Registration</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <script>
        let logout = document.getElementById('logout');
        let form = document.getElementById('form-logout');
        logout.addEventListener('click', function() {
            form.submit();
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>
