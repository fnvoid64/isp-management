@extends('layouts.app')
@section('title', 'Add Area')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Areas</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('areas') }}">Areas</a></li>
                    <li class="breadcrumb-item active">New Package</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">New Area</div>
                <div class="card-body">
                    <form method="post" action="{{ route('areas.create') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Area Name">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Create Area</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
