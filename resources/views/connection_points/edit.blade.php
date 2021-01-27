@extends('layouts.app')
@section('title', 'Edit Connection Point')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Connection Points</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('connection_points') }}">Connection Points</a></li>
                    <li class="breadcrumb-item active">Edit Connection Point</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Edit Connection Point</div>
                <div class="card-body">
                    <form method="post" action="{{ route('connection_points.edit', ['connection_point' => $connectionPoint->id]) }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $connectionPoint->name }}" placeholder="Connection Point Name">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Edit Connection Point</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
