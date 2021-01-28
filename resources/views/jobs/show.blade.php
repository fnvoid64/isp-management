@extends('layouts.app')
@section('title', 'Job Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Job Details</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jobs') }}">Jobs</a></li>
                    <li class="breadcrumb-item active">Job Details</li>
                </ol>
            </div>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Job Details
                </div>
                <div class="card-body">
                    <h2>{{ $job->name }}</h2>

                    <p><pre>{{ $job->body }}</pre></p>
                </div>
            </div>
        </div>
    </div>
@endsection

