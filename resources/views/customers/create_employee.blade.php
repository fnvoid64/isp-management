@extends('layouts.employee')
@section('title', 'Apply New Customer')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Customers</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard_v2') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee_customers') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Apply New Customer</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Apply New Customer</div>
                <div class="card-body">
                    <form method="post" action="{{ route('employee_customers.create') }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Customer Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="f_name">Father's Name</label>
                                    <input type="text" name="f_name" class="form-control" placeholder="Customer Father's Name">
                                </div>
                                <div class="form-group">
                                    <label for="m_name">Mother's Name</label>
                                    <input type="text" name="m_name" class="form-control" placeholder="Customer Mother's Name">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" placeholder="Customer Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label for="nid">NID</label>
                                    <input type="number" name="nid" class="form-control" placeholder="Customer NID Number">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" placeholder="Customer Address"></textarea>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="area">Area</label>
                                    @php $user = \App\Models\Employee::getEmployee()->user; @endphp
                                    <select name="area" class="select2 form-control" data-placeholder="Select Area">
                                        <option value="">Select Area</option>
                                        @foreach($user->areas()->get() as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="area">Connection Point</label>
                                    <select name="connection_point" class="select2 form-control" data-placeholder="Select Connection Point">
                                        <option value="">Select Connection Point</option>
                                        @foreach($user->connection_points()->get() as $connection_point)
                                            <option value="{{ $connection_point->id }}">{{ $connection_point->name }} ({{ $connection_point->area->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="package[]">Package</label>
                                    <select name="package[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose Package">
                                        @foreach($user->packages()->get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'BroadBand' : 'Cable Tv' }}) [BDT {{ $package->sale_price }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Broadband Username</label>
                                    <input type="text" name="net_user" class="form-control" placeholder="Broadband PPPoE/Other Username">
                                </div>
                                <div class="form-group">
                                    <label>Broadband Password</label>
                                    <input type="text" name="net_pass" class="form-control" placeholder="Broadband PPPoE/Other Password">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-block" type="submit">Apply New Customer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection
