@extends('layouts.app')
@section('title', 'Payment Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Payment Details</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payments') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Payment Details</li>
                </ol>
            </div>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Payment Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">ID</div>
                                    <div class="detail-value">#{{ $payment->id }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Customer</div>
                                    <div class="detail-value">
                                        <a href="{{ route('customers.show', ['customer' => $payment->customer->id]) }}">
                                            {{ $payment->customer->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Amount</div>
                                    <div class="detail-value">BDT <b>{{ $payment->amount }}</b></div>
                                </div>

                                @if ($invoices = $payment->invoices()->get())
                                    <div class="detail">
                                        <div class="detail-label">Invoices</div>
                                        <div class="detail-value">
                                            @foreach($invoices as $invoice)
                                                <a href="{{ route('invoices.show', ['invoice' => $invoice->id]) }}">Invoice #{{ $invoice->id }}</a>
                                                BDT {{ $invoice->amount }} (Due BDT {{ $invoice->due }})
                                                @if ($invoice->comment)
                                                    [{{ $invoice->comment }}]
                                                @endif
                                                <br/>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="detail">
                                    <div class="detail-label">Created By</div>
                                    <div class="detail-value">
                                        @if ($payment->employee_id)
                                            <a href="{{ route('employees.show', ['employee' => $payment->employee->id]) }}">
                                                {{ $payment->employee->name }}
                                            </a>
                                        @else
                                            {{ auth()->user()->name }} (Admin)
                                        @endif
                                    </div>
                                </div>

                                <div class="detail">
                                    <div class="detail-label">Created At</div>
                                    <div class="detail-value">{{ $payment->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <p>
                                Payment Status:
                                @if ($payment->status == \App\Models\Payment::STATUS_CONFIRMED)
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif ($payment->status == \App\Models\Payment::STATUS_PENDING)
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </p>
                            <p>
                                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#confirm">Confirm Payment</button>
                            </p>
                            <p>
                                <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#reject">Reject Payment</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirm">Confirm Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('payments.changeStatus', ['payment' => $payment->id]) }}">
                    @csrf
                    <input type="hidden" name="status" value="{{ \App\Models\Payment::STATUS_CONFIRMED }}">
                    <div class="modal-body">
                        Are you sure to confirm this payment?

                        <div class="form-group mt-2">
                            <label>PIN</label>
                            <input type="number" class="form-control" name="pin" placeholder="Account PIN">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="reject" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reject">Reject Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('payments.changeStatus', ['payment' => $payment->id]) }}">
                    @csrf
                    <input type="hidden" name="status" value="{{ \App\Models\Payment::STATUS_REJECTED }}">
                    <div class="modal-body">
                        Are you sure to reject this payment?

                        <div class="form-group mt-2">
                            <label>PIN</label>
                            <input type="number" class="form-control" name="pin" placeholder="Account PIN">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

