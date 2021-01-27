@extends('layouts.app')
@section('title', 'Package Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Packages</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('packages') }}">Packages</a></li>
                    <li class="breadcrumb-item active">Package Details</li>
                </ol>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('packages.edit', ['package' => $package->id]) }}">
                <button class="btn btn-info float-right">Edit Package</button>
            </a>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Package Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value">{{ $package->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Type</div>
                                    <div class="detail-value">{{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'Broadband' : 'Cable TV' }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Buying Price</div>
                                    <div class="detail-value">BDT {{ $package->buying_price }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Sale Price</div>
                                    <div class="detail-value">BDT {{ $package->sale_price }} ({{ round(($package->sale_price - $package->buying_price) * 100 / $package->buying_price) }}% Profit)</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Show on site</div>
                                    <div class="detail-value">{{ $package->on_site ? 'Yes' : 'No' }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Total Customers</div>
                                    <div class="detail-value">
                                        <a href="{{ route('customers') }}?package={{ $package->id }}">{{ $customer_count = $package->customers()->count() }}</a>
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
                                <a href="{{ route('customers') }}?package={{ $package->id }}">
                                <button class="btn btn-info btn-block">View Customers ({{ $customer_count }})</button>
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
