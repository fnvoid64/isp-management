@extends('layouts.app')
@section('title', 'Edit Employee')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Employees</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees') }}">Employees</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees.show', ['employee' => $employee->id]) }}">{{ $employee->name }}</a></li>
                    <li class="breadcrumb-item active">Edit Employee</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Edit Employee</div>
                <div class="card-body">
                    <form method="post" action="{{ route('employees.edit', ['employee' => $employee->id]) }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $employee->name }}" placeholder="Employee Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="f_name">Father's Name</label>
                                    <input type="text" name="f_name" class="form-control" value="{{ $employee->f_name }}" placeholder="Employee Father's Name">
                                </div>
                                <div class="form-group">
                                    <label for="m_name">Mother's Name</label>
                                    <input type="text" name="m_name" class="form-control" value="{{ $employee->m_name }}" placeholder="Employee Mother's Name">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="0{{ $employee->mobile }}" placeholder="Employee Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label for="nid">NID</label>
                                    <input type="number" name="nid" class="form-control" value="{{ $employee->nid }}" placeholder="Employee NID Number">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" placeholder="Employee Address">{{ $employee->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="area">Role</label>
                                    @php $user = auth()->user(); @endphp
                                    <select name="role" class="select2 form-control" data-placeholder="Select Employee Role">
                                        <option value="{{ \App\Models\Employee::ROLE_COLLECTOR }}" @if ($employee->role == \App\Models\Employee::ROLE_COLLECTOR) selected @endif>Collector</option>
                                    </select>
                                </div>
                                <h4 class="border-bottom my-3 py-3">Login Info</h4>
                                <div class="form-group">
                                    <label>Employee Username</label>
                                    <input type="text" class="form-control" value="{{ $employee->username }}" placeholder="Employee Username For Login" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Employee Password</label>
                                    <a href="{{ route('employees.changePassword', ['employee' => $employee]) }}">
                                        <button class="btn btn-primary btn-block" type="button">Change Password</button>
                                    </a>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-block" type="submit">Edit Employee</button>
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
