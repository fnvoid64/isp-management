@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Change Password</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.changePassword') }}">
                        @csrf
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="old_password" class="form-control" placeholder="Old Password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
