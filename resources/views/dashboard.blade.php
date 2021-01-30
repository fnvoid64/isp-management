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
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Customers</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->customer_count }}</h4>

                    </div>
                    <a href="{{ route('customers') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">View All</p>
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
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Revenue</h5>
                        <h4 class="font-weight-medium font-size-24">BDT {{ $data->total_revenue }}</h4>

                    </div>
                    <a href="{{ route('payments') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">All Payments</p>
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
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Total Dues</h5>
                        <h4 class="font-weight-medium font-size-24">BDT {{ $data->total_dues }}</h4>
                    </div>
                    <a href="{{ route('invoices') }}?status={{ \App\Models\Invoice::STATUS_UNPAID }}"
                       class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">View Unpaid Invoices</p>
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
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Packages</h5>
                        <h4 class="font-weight-medium font-size-24">{{ $data->package_count }}</h4>
                    </div>
                    <a href="{{ route('packages') }}" class="text-white-50">
                    <div class="pt-2">
                        <div class="float-right">
                            <i class="mdi mdi-arrow-right h5"></i>
                        </div>

                        <p class="text-white-50 mb-0 mt-1">All packages</p>
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
                    <h4 class="card-title mb-4">Monthly Earning</h4>
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
                                        <p class="text-muted mb-4">This month</p>
                                        <h3>BDT {{ $data->this_month_rev }}</h3>
                                        <p class="text-muted mb-5">This months payments.</p>
                                        <span class="peity-donut"
                                              data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'
                                              data-width="72" data-height="72">{{ $data->this_month_rev }}/{{ $data->total_revenue }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <p class="text-muted mb-4">Last month</p>
                                        <h3>BDT {{ $data->last_month_rev }}</h3>
                                        <p class="text-muted mb-5">Last months payments.</p>
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
                    <h4 class="card-title mb-4">Pending Customers</h4>

                    @if (count($pending_customers))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>View</th>
                                </thead>
                                <tbody>
                                @foreach($pending_customers as $customer)
                                    <tr>
                                        <td>#{{ $customer->id }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>0{{ $customer->mobile }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('customers.show', ['customer' => $customer->id]) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>
                            <a class="btn btn-primary btn-sm" href="{{ route('customers') }}?status={{ \App\Models\Customer::STATUS_PENDING }}">View all pending customers</a>
                        </p>
                    @else
                        <p>No pending customer found!</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Pending Payments</h4>

                    @if (count($pending_payments))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <th>#ID</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Collected By</th>
                                <th>View</th>
                                </thead>
                                <tbody>
                                @foreach($pending_payments as $payment)
                                    <tr>
                                        <td>#{{ $payment->id }}</td>
                                        <td>BDT {{ $payment->amount }}</td>
                                        <td>
                                            <a href="{{ route('customers.show', ['customer' => $payment->customer->id]) }}">{{ $payment->customer->name }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', ['employee' => $payment->employee->id]) }}">{{ $payment->employee->name }}</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('customers.show', ['customer' => $customer->id]) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>
                            <a class="btn btn-primary btn-sm" href="{{ route('customers') }}?status={{ \App\Models\Customer::STATUS_PENDING }}">View all pending customers</a>
                        </p>
                    @else
                        <p>No pending payment found!</p>
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
