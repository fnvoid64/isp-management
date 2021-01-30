@extends('layouts.app')
@section('title', 'Add Job')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Jobs</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jobs') }}">Jobs</a></li>
                    <li class="breadcrumb-item active">New Job</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">New Job</div>
                <div class="card-body">
                    <form method="post" action="{{ route('jobs.create') }}">
                        @csrf

                        <div class="form-group">
                            <select class="select2 form-control" name="employee">
                                <option value="">Employee</option>
                                @foreach(auth()->user()->employees()->select(['id', 'name'])->get() as $e)
                                    <option value="{{ $e->id }}" @if ($request->employee == $e->id) selected @endif>{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Job Title</label>
                            <input type="text" name="name" class="form-control" placeholder="Job Title">
                        </div>

                        <div class="form-group">
                            <label for="name">Job Description</label>
                            <textarea name="body" class="form-control" placeholder="Job Description" rows="10"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Create Job</button>
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
