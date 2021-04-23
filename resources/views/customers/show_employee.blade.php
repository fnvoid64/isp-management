@extends('layouts.employee')
@section('title', 'Customer Details')

@section('styles')
<style>
.detail-label {
    font-weight: bold;
}
</style>
@endsection

@section('content')
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title center">
                    Customer Details
                </div>
                <div class="card-content">
                    
                            <div class="collection">
                                <div class="collection-item">
                                @php $due = $customer->invoices()->whereIn('status', [\App\Models\Invoice::STATUS_UNPAID, \App\Models\Invoice::STATUS_PARTIAL_PAID])->sum('due') @endphp
                                <div class="card {{ $due > 0 ? 'red' : 'green' }} white-text">
                                    <div class="card-content">
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
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value">{{ $customer->name }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Father's Name</div>
                                    <div class="detail-value">{{ $customer->f_name }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Mother's Name</div>
                                    <div class="detail-value">{{ $customer->m_name }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Mobile Number</div>
                                    <div class="detail-value">0{{ $customer->mobile }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">NID</div>
                                    <div class="detail-value">{{ $customer->nid }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Area</div>
                                    <div class="detail-value">{{ $customer->area->name }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Connection Point</div>
                                    <div class="detail-value">{{ $customer->connection_point->name }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Address</div>
                                    <div class="detail-value">{{ $customer->address }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Package</div>
                                    <div class="detail-value">
                                    <br/>
                                        @php $is_broadband = false; @endphp
                                        @foreach($customer->packages()->get() as $package)
                                            @if ($package->type == \App\Models\Package::TYPE_BROADBAND)
                                                @php $is_broadband = true @endphp
                                            @endif
                                            <span style="padding: 4px; border-radius: 4px; color: white" class="blue">{{ $package->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($is_broadband)
                                    <div class="collection-item">
                                        <div class="detail-label">Broadband Username</div>
                                        <div class="detail-value">{{ $customer->net_user }}</div>
                                    </div>
                                    <div class="collection-item">
                                        <div class="detail-label">Broadband Password</div>
                                        <div class="detail-value">{{ $customer->net_pass }}</div>
                                    </div>
                                @endif
                                <div class="collection-item">
                                    <div class="detail-label">Total payments</div>
                                    <div class="detail-value">BDT {{ $customer->payments()->sum('amount') }}</div>
                                </div>
                                <div class="collection-item">
                                    <div class="detail-label">Member since</div>
                                    <div class="detail-value">{{ $customer->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>

                        
                            <div style="margin: 8px auto">
                            Current Status:
                                @if ($customer->status == \App\Models\Customer::STATUS_ACTIVE)
                                    <span class="new badge green mr-2" data-badge-caption="">Active</span>
                                @elseif ($customer->status == \App\Models\Customer::STATUS_PENDING)
                                    <span class="new badge grey mr-2" data-badge-caption="">Pending</span>
                                @else
                                    <span class="new badge red mr-2" data-badge-caption="">Disabled</span>
                                @endif
                            </div>
                            <div>
                                <button class="btn blue">Invoices</button>
                                <button class="btn blue">Payments</button>
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
