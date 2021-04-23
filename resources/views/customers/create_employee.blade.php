@extends('layouts.employee')
@section('title', 'Apply New Customer')

@section('content')
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-title center">Apply New Customer</div>
                <div class="card-content">
                    <form method="post" action="{{ route('employee_customers.create') }}">
                        @csrf

                        
                                <div class="input-field">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Customer Full Name">
                                </div>
                                <div class="input-field">
                                    <label for="f_name">Father's Name</label>
                                    <input type="text" name="f_name" class="form-control" placeholder="Customer Father's Name">
                                </div>
                                <div class="input-field">
                                    <label for="m_name">Mother's Name</label>
                                    <input type="text" name="m_name" class="form-control" placeholder="Customer Mother's Name">
                                </div>
                                <div class="input-field">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" placeholder="Customer Mobile Number">
                                </div>
                                <div class="input-field">
                                    <label for="nid">NID</label>
                                    <input type="number" name="nid" class="form-control" placeholder="Customer NID Number">
                                </div>
                                <div class="input-field">
                                    <label for="address">Address</label>
                                    <textarea name="address" placeholder="Customer Address" class="materialize-textarea"></textarea>
                                </div>
                            
                                <div class="input-field">
                                    @php $user = \App\Models\Employee::getEmployee()->user; @endphp
                                    <select name="area" data-placeholder="Select Area">
                                        <option value="">Select Area</option>
                                        @foreach($user->areas()->get() as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-field">
                                    <select name="connection_point">
                                        <option value="">Select Connection Point</option>
                                        @foreach($user->connection_points()->get() as $connection_point)
                                            <option value="{{ $connection_point->id }}">{{ $connection_point->name }} ({{ $connection_point->area->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-field">
                                    <label for="package[]">Package</label>
                                    <select name="package[]" multiple="multiple" data-placeholder="Choose Package">
                                        @foreach($user->packages()->get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'BroadBand' : 'Cable Tv' }}) [BDT {{ $package->sale_price }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            

                           
                                <div class="input-field">
                                    <button class="btn block" type="submit">Apply New Customer</button>
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
