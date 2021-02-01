@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="row mt-3">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="assets/images/services-icon/01.png" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">গ্রাহক</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->customer_count }}</h4>

                    </div>
                    <a href="{{ route('customers') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">সব গ্রাহক দেখুন</p>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-success text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="assets/images/services-icon/02.png" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">আয়</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->total_revenue }} টাকা</h4>

                    </div>
                    <a href="{{ route('payments') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">সব আদায় দেখুন</p>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-danger text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="assets/images/services-icon/03.png" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">বাকি</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->total_dues }} টাকা</h4>
                    </div>
                    <a href="{{ route('invoices') }}?status={{ \App\Models\Invoice::STATUS_UNPAID }}"
                       class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">সকল বাকি দেখুন</p>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-info text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="assets/images/services-icon/04.png" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">প্যাকেজ</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->package_count }}</h4>
                    </div>
                    <a href="{{ route('packages') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">সব প্যাকেজ দেখুন</p>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">মাসিক আয়ের তালিকা</h4>
                    <div class="row">
                        <div class="col-lg-7">
                            <div>
                                <div id="chart-with-area" class="ct-chart earning ct-golden-section">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <p class="text-muted mb-4">এই মাসে</p>
                                        <h3>{{ $data->this_month_rev }} টাকা</h3>
                                        <p class="text-muted mb-5">এই মাসের আয়</p>
                                        <span class="peity-donut"
                                              data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'
                                              data-width="72" data-height="72">{{ $data->this_month_rev }}/{{ $data->total_revenue }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <p class="text-muted mb-4">গত মাসে</p>
                                        <h3>{{ $data->last_month_rev }} টাকা</h3>
                                        <p class="text-muted mb-5">গত মাসের আয়</p>
                                        <span class="peity-donut"
                                              data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'
                                              data-width="72" data-height="72">{{ $data->last_month_rev }}/{{ $data->total_revenue }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">অপেক্ষমান গ্রাহক তালিকা</h4>

                    @if (count($pending_customers))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <th>#ID</th>
                                <th>নাম</th>
                                <th>মোবাইল নম্বর</th>
                                <th>ঠিকানা</th>
                                <th>দেখুন</th>
                                </thead>
                                <tbody>
                                @foreach($pending_customers as $customer)
                                    <tr>
                                        <td>#{{ $customer->id }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>0{{ $customer->mobile }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('customers.show', ['customer' => $customer->id]) }}">দেখুন</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>
                            <a class="btn btn-primary btn-sm" href="{{ route('customers') }}?status={{ \App\Models\Customer::STATUS_PENDING }}">সব অপেক্ষমান গ্রাহক দেখুন</a>
                        </p>
                    @else
                        <p>কোনো অপেক্ষমান গ্রাহক পাওয়া যায়নি।</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">অপেক্ষমান আদায়</h4>

                    @if (count($pending_payments))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <th>#ID</th>
                                <th>পরিমান</th>
                                <th>গ্রাহক</th>
                                <th>আদায় করেছেন</th>
                                <th>দেখুন</th>
                                </thead>
                                <tbody>
                                @foreach($pending_payments as $payment)
                                    <tr>
                                        <td>#{{ $payment->id }}</td>
                                        <td>{{ $payment->amount }} টাকা</td>
                                        <td>
                                            <a href="{{ route('customers.show', ['customer' => $payment->customer->id]) }}">{{ $payment->customer->name }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', ['employee' => $payment->employee->id]) }}">{{ $payment->employee->name }}</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('customers.show', ['customer' => $customer->id]) }}">দেখুন</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>
                            <a class="btn btn-primary btn-sm" href="{{ route('customers') }}?status={{ \App\Models\Customer::STATUS_PENDING }}">সব অপেক্ষমান আদায় দেখুন</a>
                        </p>
                    @else
                        <p>কোনো অপেক্ষমান আদায় পাওয়া যায়নি।</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/libs/peity/jquery.peity.min.js"></script>
    <script src="/assets/libs/chartist/chartist.min.js"></script>
    <script src="/assets/libs/chartist-plugin-tooltips/chartist-plugin-tooltip.min.js"></script>
    <script>
        new Chartist.Line("#chart-with-area",
            {labels:['{!! implode("','", $data->chart_labels) !!}'],series:[[{{ implode(',', $data->chart_data) }}]]},
            {low:0,showArea:!0,plugins:[Chartist.plugins.tooltip()]}
        );
        $(".peity-donut").each(function(){$(this).peity("donut",$(this).data())});
    </script>
@endsection
