@extends('layouts.app')
@section('title', 'Invoice Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Invoice Details</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices') }}">Invoices</a></li>
                    <li class="breadcrumb-item active">Invoice Details</li>
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
                    Invoice Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">ID</div>
                                    <div class="detail-value">#{{ $invoice->id }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Customer</div>
                                    <div class="detail-value">
                                        <a href="{{ route('customers.show', ['customer' => $invoice->customer->id]) }}">
                                            {{ $invoice->customer->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Amount</div>
                                    <div class="detail-value">BDT {{ $invoice->amount }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Due</div>
                                    <div class="detail-value">BDT {{ $invoice->customer->name }}</div>
                                </div>
                                @if ($invoice->payment_id)
                                <div class="detail">
                                    <div class="detail-label">Payments Made</div>
                                    <div class="detail-value">
                                        <a href="">Payemnt #{{ $invoice->payment->id }}</a> BDT {{ $invoice->payment->amount }} at {{ $invoice->payment->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                @endif
                                <div class="detail">
                                    <div class="detail-label">Packages</div>
                                    <div class="detail-value">
                                        @foreach(auth()->user()->packages()->whereIn('id', explode(',', $invoice->package_ids))->get() as $package)
                                            {{ $package->name }} (BDT {{ $package->sale_price }}),
                                        @endforeach
                                    </div>
                                </div>

                                <div class="detail">
                                    <div class="detail-label">Created At</div>
                                    <div class="detail-value">{{ $package->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <a href="">
                                    <button class="btn btn-info btn-block">View Customers</button>
                                </a>
                            </p>
                            <p>
                                <button class="btn btn-primary btn-block">View Invoices</button>
                            </p>
                            <p>
                                <button class="btn btn-success btn-block">View Payments</button>
                            </p>
                            <p>
                                <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete">Delete Package</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete">Delete Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('packages.delete', ['package' => $package->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger">Are you sure you want to delete package {{ $package->name }}?</p>
                        <div class="form-group">
                            <label>PIN</label>
                            <input type="number" class="form-control" name="pin" placeholder="Account PIN" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

