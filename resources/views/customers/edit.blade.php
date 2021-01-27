@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Customers</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers') }}">Customers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.show', ['customer' => $customer->id]) }}">{{ $customer->name }}</a></li>
                    <li class="breadcrumb-item active">Edit Customer</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Edit Customer</div>
                <div class="card-body">
                    <form method="post" action="{{ route('customers.edit', ['customer' => $customer]) }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $customer->name }}" placeholder="Customer Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="f_name">Father's Name</label>
                                    <input type="text" name="f_name" class="form-control" value="{{ $customer->f_name }}" placeholder="Customer Father's Name">
                                </div>
                                <div class="form-group">
                                    <label for="m_name">Mother's Name</label>
                                    <input type="text" name="m_name" class="form-control" value="{{ $customer->m_name }}" placeholder="Customer Mother's Name">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="0{{ $customer->mobile }}" placeholder="Customer Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label for="nid">NID</label>
                                    <input type="number" name="nid" class="form-control" value="{{ $customer->nid }}" placeholder="Customer NID Number">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" placeholder="Customer Address">{{ $customer->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="area">Area</label>
                                    @php $user = auth()->user(); @endphp
                                    <select name="area" class="select2 area form-control" data-placeholder="Select Area">
                                        @foreach($user->areas()->get() as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="area">Connection Point</label>
                                    <select name="connection_point" class="select2 cp form-control" data-placeholder="Select Connection Point">
                                        @foreach($user->connection_points()->get() as $connection_point)
                                            <option value="{{ $connection_point->id }}">{{ $connection_point->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="package[]">Package</label>
                                    <select name="package[]" class="select2 package form-control select2-multiple" multiple="multiple" data-placeholder="Choose Package">
                                        @foreach($user->packages()->get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Broadband Username</label>
                                    <input type="text" name="net_user" class="form-control" value="{{ $customer->net_user }}" placeholder="Broadband PPPoE/Other Username">
                                </div>
                                <div class="form-group">
                                    <label>Broadband Password</label>
                                    <input type="text" name="net_pass" class="form-control" value="{{ $customer->net_pass }}" placeholder="Broadband PPPoE/Other Password">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Edit Customer</button>
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
        var PACKAGE_LIST = [
            @foreach($customer->packages()->select('packages.id')->get() as $package)
            {{ $package->id }},
            @endforeach
        ];

        $('.select2').select2();
        $('.package').val(PACKAGE_LIST).trigger('change');
        $('.area').val({{ $customer->area_id }}).trigger('change');
        $('.cp').val({{ $customer->connection_point_id }}).trigger('change');
    </script>
@endsection
