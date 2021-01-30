<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('APP_NAME'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pranto" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <link href="/assets/libs/chartist/chartist.min.css" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <style>
        .details-box {
            border: 1px solid #ececec;
            border-right: 0;
        }

        .detail {
            border-bottom: 1px solid #ececec;
            display: flex;
            justify-content: space-between;
        }

        .detail-label {
            padding: 6px 10px;
            border-right: 1px solid #ececec;
            font-weight: bold;
            flex: 1;
        }

        .detail-value {
            padding: 6px 10px;
            border-right: 1px solid #ececec;
            flex: 2;
        }
    </style>
    @yield('styles')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- App Css-->
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

<body data-sidebar="dark">

<!-- Begin page -->
<div id="layout-wrapper">
    @php $employee = \App\Models\Employee::getEmployee(); $user= $employee->user; @endphp
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm logoText">
                                    {{ strtoupper($user->company_short) }}
                                </span>
                        <span class="logo-lg logoText">
                                    {{ $user->company_name }}
                                </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                                <span class="logo-sm logoText">
                                    {{ strtoupper($user->company_short) }}
                                </span>
                        <span class="logo-lg logoText">
                                    {{ $user->company_name }}
                                </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>

            <div class="d-flex">
                <div class="dropdown d-none d-lg-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                        <i class="mdi mdi-fullscreen"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="badge badge-danger badge-pill">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                         aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="m-0 font-size-16"> Notifications (258) </h5>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            <a href="" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">Your order is placed</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">Dummy text of the printing and typesetting industry.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-warning rounded-circle font-size-16">
                                                    <i class="mdi mdi-message-text-outline"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">New Message received</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">You have 87 unread messages</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-info rounded-circle font-size-16">
                                                    <i class="mdi mdi-glass-cocktail"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">Your item is shipped</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">It is a long established fact that a reader will</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="mdi mdi-cart-outline"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">Your order is placed</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">Dummy text of the printing and typesetting industry.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-danger rounded-circle font-size-16">
                                                    <i class="mdi mdi-message-text-outline"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">New Message received</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">You have 87 unread messages</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="javascript:void(0)">
                                View all
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ $employee->photo ? asset('/storage/' . $employee->photo) : '/assets/images/profile-min.png' }}"
                             alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-wallet font-size-17 align-middle mr-1"></i> My Wallet</a>
                        <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings font-size-17 align-middle mr-1"></i> Settings</a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline font-size-17 align-middle mr-1"></i> Lock screen</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('employee_logout') }}">
                            <i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i> Logout
                        </a>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                        <i class="mdi mdi-settings-outline"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">
        @php
            $_nav_links = [
                'Dashboard' => [
                    'i' => 'home',
                    'r' => route('dashboard_v2')
                ],
                'Customers' => [
                    'i' => 'user',
                    'r' => [
                        'All Customers' => route('employee_customers'),
                        'Active Customers' => route('employee_customers') . "?status=" . \App\Models\Customer::STATUS_ACTIVE,
                        'Pending Customers' => route('employee_customers') . "?status=" . \App\Models\Customer::STATUS_PENDING,
                        'Apply Customer' => route('employee_customers.create')
                    ]
                ],
                'Payments' => [
                    'i' => 'money',
                    'r' => [
                        'All Payments' => route('employee_payments'),
                        'Cash Payments' => route('employee_payments') . "?type=" . \App\Models\Payment::TYPE_CASH,
                        'bKash/Rocket/Nagad Payments' => route('employee_payments') . "?type=" . \App\Models\Payment::TYPE_MOBILE_BANK,
                        'Bank Payments' => route('employee_payments') . "?type=" . \App\Models\Payment::TYPE_BANK
                    ]
                ],
                'Invoices' => [
                    'i' => 'file',
                    'r' => [
                        'All Invoices' => route('employee_invoices'),
                        'Paid Invoices' => route('employee_invoices') . "?status=" . \App\Models\Invoice::STATUS_PAID,
                        'Unpaid Invoices' => route('employee_invoices') . "?status=" . \App\Models\Invoice::STATUS_UNPAID,
                        'Partially Paid Invoices' => route('employee_invoices') . "?status=" . \App\Models\Invoice::STATUS_PARTIAL_PAID,
                        'Cancelled Invoices' => route('employee_invoices') . "?status=" . \App\Models\Invoice::STATUS_CANCELLED
                    ]
                ],
                'Jobs' => [
                    'i' => 'settings',
                    'r' => [
                        'All Jobs' => route('employee_jobs'),
                        'Completed Jobs' => route('employee_jobs') . "?status=" . \App\Models\Job::STATUS_DONE,
                        'Pending Jobs' => route('employee_jobs') . "?status=" . \App\Models\Job::STATUS_PENDING,
                        'Cancelled Jobs' => route('employee_jobs') . "?status=" . \App\Models\Job::STATUS_CANCELLED
                    ]
                ],
            ];
        @endphp
        <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Main</li>

                    @foreach($_nav_links as $k => $l)
                        @if (is_array($l['r']))
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ti-{{ $l['i'] }}"></i>
                                    <span>{{ $k }}</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @foreach($l['r'] as $n => $li)
                                        <li><a href="{{ $li }}">{{ $n }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $l['r'] }}" class="waves-effect">
                                    <i class="ti-{{ $l['i'] }}"></i>
                                    <span>{{ $k }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" id="vue">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row mt-2">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="ti-arrow-left"></i> Go Back
                    </a>
                </div>
                @yield('content')
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->



        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        © <script>document.write(new Date().getFullYear())</script> Crafted with <i class="mdi mdi-heart text-danger"></i> by Pranto.</span>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!-- end main content-->

</div>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/js/app.js"></script>
@yield('scripts')

</body>
</html>
