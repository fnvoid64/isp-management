@extends('layouts.app')
@section('title', 'Employee Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Employees</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employees') }}">Employees</a></li>
                    <li class="breadcrumb-item active">Employee Details</li>
                </ol>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('employees.edit', ['employee' => $employee->id]) }}">
                <button class="btn btn-info float-right">Edit Employee</button>
            </a>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Employee Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value">{{ $employee->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Father's Name</div>
                                    <div class="detail-value">{{ $employee->f_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mother's Name</div>
                                    <div class="detail-value">{{ $employee->m_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mobile Number</div>
                                    <div class="detail-value">0{{ $employee->mobile }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">NID</div>
                                    <div class="detail-value">{{ $employee->nid }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Address</div>
                                    <div class="detail-value">{{ $employee->address }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Login Username</div>
                                    <div class="detail-value"><b>{{ $employee->username }}</b></div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Login Password</div>
                                    <div class="detail-value">
                                        <a href="{{ route('employees.changePassword', ['employee' => $employee]) }}">
                                            <b>Change Password</b>
                                        </a>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Total Collected</div>
                                    <div class="detail-value">BDT {{ $employee->payments()->sum('amount') }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Member since</div>
                                    <div class="detail-value">{{ $employee->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p>
                            <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Current Status:
                            <b class="text-success mr-2" v-if="status == 1">Active</b>
                            <b class="text-warning mr-2" v-else-if="status == 2">Pending</b>
                            <b class="text-danger mr-2" v-else>Disabled</b>

                            <button class="btn btn-danger btn-sm mr-1" v-if="status == 1 || status == 2" @click="changeStatus(0)" :class="{disabled: pageLoading}">Disable</button>
                            <button class="btn btn-success btn-sm" v-if="status == 0 || status == 2" @click="changeStatus(1)" :class="{disabled: pageLoading}">Activate</button>
                            </p>
                            <p>
                                <a href="{{ route('employees.changePassword', ['employee' => $employee]) }}">
                                    <button class="btn btn-primary btn-block">Change Password</button>
                                </a>
                            </p>
                            <p>
                                <a href="{{ route('jobs.create') }}?employee={{ $employee->id }}">
                                    <button class="btn btn-info btn-block">Assign Job</button>
                                </a>
                            </p>
                            <p>
                                <button class="btn btn-success btn-block">View Payments</button>
                            </p>
                            <p>
                                <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete">Delete Employee</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete">Delete Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('employees.delete', ['employee' => $employee->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger">Are you sure you want to delete employee {{ $employee->name }}?</p>
                        <div class="form-group">
                            <label>PIN</label>
                            <input type="number" class="form-control" name="pin" placeholder="Account PIN" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    status: {{ $employee->status }},
                    pageLoading: false
                }
            },
            methods: {
                changeStatus(newStatus) {
                    this.pageLoading = true;
                    axios.post('{{ route('employees.changeStatus', ['employee' => $employee->id]) }}', {
                        status: newStatus
                    }).then(response => {
                        this.status = response.data;
                        this.pageLoading = false;
                        showAlertMsg('Employee status changed!');
                    }).catch(function (error) {
                        showAlertMsg('Cannot change employee status', 'error');
                        this.pageLoading = false;
                    });
                }
            }
        }

        Vue.createApp(App).mount('#vue');
    </script>
@endsection
