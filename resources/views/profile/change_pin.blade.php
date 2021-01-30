@extends('layouts.app')
@section('title', 'Change PIN')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Change PIN</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change PIN</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Change PIN</div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.changePin') }}">
                        @csrf
                        <div class="form-group">
                            <label>Old PIN</label>
                            <input type="number" name="old_pin" class="form-control" placeholder="Old PIN">
                        </div>
                        <div class="form-group">
                            <label>New PIN</label>
                            <input type="number" name="pin" class="form-control" placeholder="New PIN">
                        </div>
                        <div class="form-group">
                            <label>Confirm New PIN</label>
                            <input type="number" name="pin_confirmation" class="form-control" placeholder="Confirm New PIN">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Change PIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
