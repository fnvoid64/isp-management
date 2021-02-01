<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('APP_NAME'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="ISP and Cable TV Billing Systems" name="description" />
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

        .logoText {
            color: #fff;
            font-size: 1.3em;
        }
    </style>
    @yield('styles')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <script src="/assets/libs/jquery/jquery.min.js"></script>

    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Kalpurush', 'Roboto', sans-serif;
        }
    </style>

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
    @php
    $notifications = [];
    $user = auth()->user();
    $pending_customers = $user->customers()->where('status', \App\Models\Customer::STATUS_PENDING)->count();
    $pending_payments = $user->payments()->where('status', \App\Models\Payment::STATUS_PENDING)->count();

    if ($pending_customers > 0) {
        $notifications[] = [
            'link' => route('customers') . '?status=' . \App\Models\Customer::STATUS_PENDING,
            'text' => $pending_customers . ' টি পেন্ডিং গ্রাহক রয়েছেন। তাদের সক্রিয় করুন',
            'icon' => 'account-supervisor-outline',
            'header' => 'অপেক্ষমান গ্রাহকরা'
        ];
    }

    if ($pending_payments> 0) {
        $notifications[] = [
            'link' => route('payments') . '?status=' . \App\Models\Payment::STATUS_PENDING,
            'text' => 'There are ' . $pending_payments . ' pending payments. Activate them.',
            'icon' => 'cash-multiple',
            'header' => 'Pending Payments'
        ];
    }
    @endphp
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm logoText">
                                    {{ strtoupper($user->company_short) }}
                                </span>
                        <span class="logo-lg logoText">
                                    {{ $user->company_name }}
                                </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light">
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

                <div class="d-none d-sm-block">
                    <div class="dropdown pt-3 d-inline-block">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            তৈরী করুন <i class="mdi mdi-chevron-down"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('customers.create') }}">গ্রাহক</a>
                            <a class="dropdown-item" href="{{ route('packages.create') }}">প্যাকেজ</a>
                            <a class="dropdown-item" href="{{ route('areas.create') }}">এলাকা</a>
                            <a class="dropdown-item" href="{{ route('connection_points.create') }}">সংযোগস্থল</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <div class="dropdown d-none d-lg-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                        <i class="mdi mdi-fullscreen"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" disabled>
                        <b>{{ $user->sms }}</b> SMS
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-bell-outline"></i>
                        @if ($notifications)
                            <span class="badge badge-danger badge-pill">{{ count($notifications) }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                         aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="m-0 font-size-16"> বিজ্ঞপ্তি ({{ $pending_customers + $pending_payments }}) </h5>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            @if ($notifications)
                                @foreach($notifications as $n)
                            <a href="{{ $n['link'] }}" class="text-reset notification-item">
                                <div class="media">
                                    <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                                    <i class="mdi mdi-{{ $n['icon'] }}"></i>
                                                </span>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1">{{ $n['header'] }}</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">{{ $n['text'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                                @endforeach
                        @else
                        <div class="p-2 border-top">
                            বিজ্ঞপ্তি নেই
                        </div>
                        @endif
                        </div>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{ $user->photo ? asset('/storage/' . $user->photo) : '/assets/images/profile-min.png' }}"
                             alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <a class="dropdown-item" href="{{ route('profile') }}"><i class="mdi mdi-account-circle font-size-17 align-middle mr-1"></i> প্রোফাইল সম্পাদনা</a>
                        <a class="dropdown-item d-block" href="{{ route('profile.changePassword') }}"><i class="mdi mdi-settings font-size-17 align-middle mr-1"></i> পাসওয়ার্ড পরিবর্তন</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                            <i class="bx bx-power-off font-size-17 align-middle mr-1 text-danger"></i> বের হয়ে যান
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">
            @php
            $_nav_links = [
                'ড্যাশবোর্ড' => [
                    'i' => 'home',
                    'r' => route('dashboard')
                ],
                'গ্রাহকরা' => [
                    'i' => 'user',
                    'r' => [
                        'সব গ্রাহক' => route('customers'),
                        'সক্রিয় গ্রাহকরা' => route('customers') . "?status=" . \App\Models\Customer::STATUS_ACTIVE,
                        'অপেক্ষমান গ্রাহকরা' => route('customers') . "?status=" . \App\Models\Customer::STATUS_PENDING,
                        'গ্রাহক তৈরি করুন' => route('customers.create')
                    ]
                ],
                'প্যাকেজ' => [
                    'i' => 'package',
                    'r' => [
                        'সমস্ত প্যাকেজ' => route('packages'),
                        'ব্রডব্যান্ড প্যাকেজ' => route('packages') . "?type=" . \App\Models\Package::TYPE_BROADBAND,
                        'কেবল টিভি প্যাকেজ' => route('packages') . "?type=" . \App\Models\Package::TYPE_CABLE_TV,
                        'প্যাকেজ তৈরি করুন' => route('packages.create')
                    ]
                ],
                'এলাকা' => [
                    'i' => 'location-pin',
                    'r' => [
                        'সব এলাকা' => route('areas'),
                        'এলাকা তৈরি করুন' => route('areas.create')
                    ]
                ],
                'সংযোগস্থল' => [
                    'i' => 'direction',
                    'r' => [
                        'সব সংযোগস্থল' => route('connection_points'),
                        'সংযোগস্থল তৈরি করুন' => route('connection_points.create')
                    ]
                ],
                'আদায়' => [
                    'i' => 'money',
                    'r' => [
                        'সব আদায়' => route('payments'),
                        'ক্যাশ আদায়' => route('payments') . "?type=" . \App\Models\Payment::TYPE_CASH,
                        'বিকাশ/নগদ/রকেট আদায়' => route('payments') . "?type=" . \App\Models\Payment::TYPE_MOBILE_BANK,
                        'ব্যাংক আদায়' => route('payments') . "?type=" . \App\Models\Payment::TYPE_BANK
                    ]
                ],
                'ইনভয়েস' => [
                    'i' => 'file',
                    'r' => [
                        'সব ইনভয়েস' => route('invoices'),
                        'পরিশোধকৃত ইনভয়েস' => route('invoices') . "?status=" . \App\Models\Invoice::STATUS_PAID,
                        'বাকি ইনভয়েস' => route('invoices') . "?status=" . \App\Models\Invoice::STATUS_UNPAID,
                        'আংশিক পরিশোধ ইনভয়েস' => route('invoices') . "?status=" . \App\Models\Invoice::STATUS_PARTIAL_PAID,
                        'বাতিল করা ইনভয়েস' => route('invoices') . "?status=" . \App\Models\Invoice::STATUS_CANCELLED
                    ]
                ],
                'হিসাব' => [
                    'i' => 'pie-chart',
                    'r' => route('statistics')
                ],
                'কর্মচারী' => [
                    'i' => 'user',
                    'r' => [
                        'সব কর্মচারী' => route('employees'),
                        'সক্রিয় কর্মচারী ' => route('employees') . "?status=" . \App\Models\Employee::STATUS_ACTIVE,
                        'অপেক্ষমান কর্মচারী' => route('employees') . "?status=" . \App\Models\Employee::STATUS_PENDING,
                        'কর্মচারী তৈরি করুন' => route('employees.create')
                    ]
                ],
                'কর্মচারীর কাজ' => [
                    'i' => 'briefcase',
                    'r' => [
                        'সব কাজ' => route('jobs'),
                        'সম্পন্ন কাজ' => route('jobs') . "?status=" . \App\Models\Job::STATUS_DONE,
                        'অপেক্ষমান কাজ' => route('jobs') . "?status=" . \App\Models\Job::STATUS_PENDING,
                        'বাতিল কাজ' => route('jobs') . "?status=" . \App\Models\Job::STATUS_CANCELLED,
                        'কাজ তৈরী করুন' => route('jobs.create')
                    ]
                ],
                'এসএমএস পাঠান' => [
                    'i' => 'email',
                    'r' => route('sms.create')
                ],
                'আমার প্রোফাইল' => [
                    'i' => 'settings',
                    'r' => [
                        'প্রোফাইল পরিবর্তন' => route('profile'),
                        'পাসওয়ার্ড পরিবর্তন' => route('profile.changePassword'),
                        'পিন পরিবর্তন' => route('profile.changePin')
                    ]
                ],
                'বের হয়ে যান' => [
                    'i' => 'close',
                    'r' => route('logout')
                ],
            ];
            @endphp
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">মূল মেনু</li>

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
                        <i class="ti-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="javascript:history.forward()" class="btn btn-secondary ml-2">
                        সামনে যান <i class="ti-arrow-right"></i>
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
