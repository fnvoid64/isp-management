@extends('layouts.app')
@section('title', 'Add Employee')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Employees</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers') }}">Employees</a></li>
                    <li class="breadcrumb-item active">New Employee</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">New Employee</div>
                <div class="card-body">
                    <form method="post" action="{{ route('employees.create') }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Employee Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="f_name">Father's Name</label>
                                    <input type="text" name="f_name" class="form-control" placeholder="Employee Father's Name">
                                </div>
                                <div class="form-group">
                                    <label for="m_name">Mother's Name</label>
                                    <input type="text" name="m_name" class="form-control" placeholder="Employee Mother's Name">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" placeholder="Employee Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label for="nid">NID</label>
                                    <input type="number" name="nid" class="form-control" placeholder="Employee NID Number">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" placeholder="Employee Address"></textarea>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="area">Role</label>
                                    @php $user = auth()->user(); @endphp
                                    <select name="role" class="select2 form-control" data-placeholder="Select Employee Role">
                                        <option value="{{ \App\Models\Employee::ROLE_COLLECTOR }}">Collector</option>
                                    </select>
                                </div>
                                <h4 class="border-bottom my-3 py-3">Login Info</h4>
                                <div class="form-group">
                                    <label>Employee Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Employee Username For Login">
                                </div>
                                <div class="form-group">
                                    <label>Employee Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Employee Password For Login">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Employee Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Employee Password">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-block" type="submit">Create Employee</button>
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
