@extends('layouts.employee')
@section('title', 'Customer Details')

@section('content')
    <div class="row align-items-center">
        <div class="page-title-box">
            <h4 class="font-size-18">Customers</h4>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard_v2') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employee_customers') }}">Customers</a></li>
                <li class="breadcrumb-item active">Customer Details</li>
            </ol>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Customer Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value">{{ $customer->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Father's Name</div>
                                    <div class="detail-value">{{ $customer->f_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mother's Name</div>
                                    <div class="detail-value">{{ $customer->m_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mobile Number</div>
                                    <div class="detail-value">0{{ $customer->mobile }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">NID</div>
                                    <div class="detail-value">{{ $customer->nid }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Area</div>
                                    <div class="detail-value">{{ $customer->area->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Connection Point</div>
                                    <div class="detail-value">{{ $customer->connection_point->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Address</div>
                                    <div class="detail-value">{{ $customer->address }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Package</div>
                                    <div class="detail-value">
                                        @php $is_broadband = false; @endphp
                                        @foreach($customer->packages()->get() as $package)
                                            @if ($package->type == \App\Models\Package::TYPE_BROADBAND)
                                                @php $is_broadband = true @endphp
                                            @endif
                                            <a href="{{ route('packages.show', ['package' => $package->id]) }}">
                                                <button class="btn btn-secondary btn-sm m-1">{{ $package->name }}</button>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($is_broadband)
                                    <div class="detail">
                                        <div class="detail-label">Broadband Username</div>
                                        <div class="detail-value">{{ $customer->net_user }}</div>
                                    </div>
                                    <div class="detail">
                                        <div class="detail-label">Broadband Password</div>
                                        <div class="detail-value">{{ $customer->net_pass }}</div>
                                    </div>
                                @endif
                                <div class="detail">
                                    <div class="detail-label">Total payments</div>
                                    <div class="detail-value">BDT {{ $customer->payments()->sum('amount') }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Member since</div>
                                    <div class="detail-value">{{ $customer->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php $due = $customer->invoices()->whereIn('status', [\App\Models\Invoice::STATUS_UNPAID, \App\Models\Invoice::STATUS_PARTIAL_PAID])->sum('due') @endphp
                            <div class="card mini-stat bg-{{ $due > 0 ? 'danger' : 'success' }} text-white">
                                <div class="card-body">
                                    <div class="float-left mini-stat-img mr-4">
                                        <img src="/assets/images/services-icon/02.png" alt="">
                                    </div>
                                    <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Due
                                        @if ($due > 0)
                                            <button type="button" class="btn btn-secondary btn-sm ml-1" data-toggle="modal" data-target="#payment">
                                                Pay Now
                                            </button>
                                        @endif
                                    </h5>
                                    <h4 class="font-weight-medium font-size-24">BDT {{ $due }}</h4>

                                </div>
                            </div>
                            <p>
                            Current Status:
                                @if ($customer->status == \App\Models\Customer::STATUS_ACTIVE)
                                    <span class="badge badge-success mr-2">Active</span>
                                @elseif ($customer->status == \App\Models\Customer::STATUS_PENDING)
                                    <span class="badge badge-warning mr-2">Pending</span>
                                @else
                                    <span class="badge badge-danger mr-2">Disabled</span>
                                @endif
                            </p>
                            <p>
                                <button class="btn btn-primary btn-block">View Invoices</button>
                            </p>
                            <p>
                                <button class="btn btn-success btn-block">View Payments</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payment">Make Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('employee_customers.makePayment', ['customer' => $customer->id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Amount (BDT)</label>
                            <input type="number" class="form-control" name="amount" placeholder="Payment Amount" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select name="type" class="form-control">
                                <option value="{{ \App\Models\Payment::TYPE_CASH }}">Cash</option>
                                <option value="{{ \App\Models\Payment::TYPE_MOBILE_BANK }}">bKash/Rocket/Nagad Etc</option>
                                <option value="{{ \App\Models\Payment::TYPE_BANK }}">Bank</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Make Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
