@extends('layouts.app')
@section('title', 'Add Connection Point')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Connection Points</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('connection_points') }}">Connection Points</a></li>
                    <li class="breadcrumb-item active">New Connection Point</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">New Connection Point</div>
                <div class="card-body">
                    <form method="post" action="{{ route('connection_points.create') }}">
                        @csrf

                        <div class="form-group">
                            <select class="form-control" name="area">
                                <option value="">Area</option>
                                @foreach(auth()->user()->areas()->get() as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Connection Point Name">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Create Connection Point</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
