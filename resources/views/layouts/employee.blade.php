<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('APP_NAME'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="/assets/libs/jquery/jquery.min.js"></script>
    
    <style>
    .btm-nav {
        background: #ddd;
        height: 50px;
        width: 100vw;
        position: fixed;
        bottom: 0;
        left: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-left: 10px;
        padding-right: 10px;
        z-index: 999;
    }
    .container {
        margin-top: 70px;
        margin-bottom: 55px;
    }
    </style>
    @yield('styles')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        function showAlertMsg(msg, type = 'success') {
            Swal.fire({
                type: type,
                title: type.toUpperCase() + '!',
                html: msg
            });
        }
    </script>
</head>

<body>
@php $employee = \App\Models\Employee::getEmployee(); $user= $employee->user; @endphp
<nav class="navbar-fixed teal lighten-1" style="position: fixed; top: 0; leeft: 0; z-index: 999">
    <div class="nav-wrapper">
        <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <a href="#!" class="brand-logo">CMSDust</a>
        <ul class="right">
            <li>
            <img src="{{ $employee->photo ? asset('/storage/' . $employee->photo) : '/assets/images/profile-min.png' }}" width="42" style="border-radius: 50%; margin: 5px 10px">
            </li>
        </ul>
    </div>
</nav>

<div class="btm-nav teal lighten-2">
<a href="javascript:history.back();" class="btn waves-effect waves-light" type="button">
<i class="material-icons">chevron_left</i>
</a>
<a href="{{ route('dashboard_v2') }}" class="btn waves-effect waves-light" type="button">
    <i class="material-icons left">home</i> Home
</a>
<a href="javascript:history.forward()" class="btn waves-effect waves-light" type="button">
    <i class="material-icons">chevron_right</i>
</a>
</div>

<ul id="slide-out" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="/assets/images/bg-profile.jpg">
            </div>
            <a href="#user"><img class="circle" src="{{ $employee->photo ? asset('/storage/' . $employee->photo) : '/assets/images/profile-min.png' }}"></a>
            <a href="#name"><span class="white-text name">{{ $employee->name }}</span></a>
            <a href="#email"><span class="white-text email">0{{ $employee->mobile }}</span></a>
        </div>
    </li>
    <li><a href="{{ route('dashboard_v2') }}"><i class="material-icons">dashboard</i>Dashboard</a></li>
    <li><a href="{{ route('employee_customers') }}"><i class="material-icons">account_circle</i>Customers</a></li>
    <li><a href="{{ route('employee_payments') }}"><i class="material-icons">payment</i>Payments</a></li>
    <li><a href="{{ route('employee_invoices') }}"><i class="material-icons">assignment</i>Invoices</a></li>
    <li><a href="{{ route('printers') }}"><i class="material-icons">print</i>Printers</a></li>
    <li><a href="{{ route('employee_jobs') }}"><i class="material-icons">settings</i>My Jobs</a></li>
</ul>

<div class="container" id="vue">@yield('content')</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
  $(document).ready(function(){
    $('.sidenav').sidenav();
  });
  $(document).ready(function(){
    $('select').formSelect();
  });
  </script>
@yield('scripts')

</body>
</html>
