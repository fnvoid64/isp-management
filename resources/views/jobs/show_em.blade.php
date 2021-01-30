@extends('layouts.employee')
@section('title', 'Job Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Job Details</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee_jobs') }}">Jobs</a></li>
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
                    <p>Created at: {{ $job->created_at->diffForHumans() }} Status:
                        @if ($job->status == \App\Models\Job::STATUS_DONE)
                            <span class="badge badge-success">Completed</span>
                        @elseif ($job->status == \App\Models\Job::STATUS_PENDING)
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-secondary">Cancelled</span>
                        @endif
                    </p>

                    <p><pre>{{ $job->body }}</pre></p>

                    @if ($job->status == \App\Models\Job::STATUS_PENDING)
                        <p>
                            <button class="btn btn-success" data-toggle="modal" data-target="#complete">Mark as completed</button>
                        </p>

                        <div class="modal fade" id="complete" tabindex="-1" role="dialog" aria-labelledby="complete" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete">Mark job as complete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post" action="{{ route('employee_jobs.markComplete', ['job' => $job->id]) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-danger">Are you sure you want to mark this job as complete?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-success">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

