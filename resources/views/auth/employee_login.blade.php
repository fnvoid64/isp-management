<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Employee Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Pranto" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <script src="/assets/libs/jquery/jquery.min.js"></script>

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

<body class="bg-success">
<div class="account-pages my-5 pt-5 bg-success">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-dark" style="outline: none">
                        <div class="text-success text-center p-4">
                            <h5 class="text-white font-size-20">Welcome Back !</h5>
                            <p class="text-white-50">Sign in to continue to dashboard.</p>
                            <p class="logo logo-admin">
                                <i class="fa fa-lock fa-2x text-success"></i>
                            </p>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="p-3">

                            @include('includes.errors')

                            <form class="form-horizontal mt-4" method="post" action="{{ route('employee_login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Enter username">
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter password">
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-success btn-block w-md waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="mt-5 text-center">
                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Developed By <i class="mdi mdi-heart text-danger"></i> Pranto</p>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
