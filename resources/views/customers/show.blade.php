@extends('layouts.app')
@section('title', 'Customer Details')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Customers</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Customer Details</li>
                </ol>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('customers.edit', ['customer' => $customer->id]) }}">
                <button class="btn btn-info float-right">Edit Customer</button>
            </a>
        </div>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Customer Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="details-box">
                                <div class="detail">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value">{{ $customer->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Father's Name</div>
                                    <div class="detail-value">{{ $customer->f_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mother's Name</div>
                                    <div class="detail-value">{{ $customer->m_name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Mobile Number</div>
                                    <div class="detail-value">0{{ $customer->mobile }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">NID</div>
                                    <div class="detail-value">{{ $customer->nid }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Area</div>
                                    <div class="detail-value">{{ $customer->area->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Connection Point</div>
                                    <div class="detail-value">{{ $customer->connection_point->name }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Address</div>
                                    <div class="detail-value">{{ $customer->address }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Package</div>
                                    <div class="detail-value">
                                        @php $is_broadband = false; @endphp
                                        @foreach($customer->packages()->get() as $package)
                                            @if ($package->type == \App\Models\Package::TYPE_BROADBAND)
                                            @php $is_broadband = true @endphp
                                            @endif
                                            <a href="{{ route('packages.show', ['package' => $package->id]) }}">
                                                <button class="btn btn-secondary btn-sm m-1">{{ $package->name }}</button>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($is_broadband)
                                <div class="detail">
                                    <div class="detail-label">Broadband Username</div>
                                    <div class="detail-value">{{ $customer->net_user }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Broadband Password</div>
                                    <div class="detail-value">{{ $customer->net_pass }}</div>
                                </div>
                                @endif
                                <div class="detail">
                                    <div class="detail-label">Total payments</div>
                                    <div class="detail-value">BDT {{ $customer->payments()->sum('amount') }}</div>
                                </div>
                                <div class="detail">
                                    <div class="detail-label">Member since</div>
                                    <div class="detail-value">{{ $customer->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php $due = $customer->invoices()->whereIn('status', [\App\Models\Invoice::STATUS_UNPAID, \App\Models\Invoice::STATUS_PARTIAL_PAID])->sum('due') @endphp
                            <div class="card mini-stat bg-{{ $due > 0 ? 'danger' : 'success' }} text-white">
                                <div class="card-body">
                                    <div class="float-left mini-stat-img mr-4">
                                        <img src="/assets/images/services-icon/02.png" alt="">
                                    </div>
                                    <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Due
                                        @if ($due > 0)
                                            <button type="button" class="btn btn-secondary btn-sm ml-1" data-toggle="modal" data-target="#payment">
                                                Pay Now
                                            </button>
                                        @endif
                                    </h5>
                                    <h4 class="font-weight-medium font-size-24">BDT {{ $due }}</h4>

                                </div>
                            </div>
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
                                <button class="btn btn-primary btn-block">View Invoices</button>
                            </p>
                            <p>
                                <button class="btn btn-success btn-block">View Payments</button>
                            </p>
                            <p>
                                <button class="btn btn-info btn-block">Send SMS</button>
                            </p>
                            <p>
                                <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete">Delete Customer</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payment">Make Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('customers.makePayment', ['customer' => $customer->id]) }}">
                    @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Amount (BDT)</label>
                        <input type="number" class="form-control" name="amount" placeholder="Payment Amount" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Type</label>
                        <select name="type" class="form-control">
                            <option value="{{ \App\Models\Payment::TYPE_CASH }}">Cash</option>
                            <option value="{{ \App\Models\Payment::TYPE_MOBILE_BANK }}">bKash/Rocket/Nagad Etc</option>
                            <option value="{{ \App\Models\Payment::TYPE_BANK }}">Bank</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Make Payment</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete">Delete Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('customers.delete', ['customer' => $customer->id]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger">Are you sure you want to delete customer {{ $customer->name }}?</p>
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
                    status: {{ $customer->status }},
                    pageLoading: false
                }
            },
            methods: {
                changeStatus(newStatus) {
                    this.pageLoading = true;
                    axios.post('{{ route('customers.changeStatus', ['customer' => $customer->id]) }}', {
                        status: newStatus
                    }).then(response => {
                        this.status = response.data;
                        this.pageLoading = false;
                    }).catch(function (error) {
                        console.log(error);
                        this.pageLoading = false;
                    });
                }
            }
        }

        Vue.createApp(App).mount('#vue');
    </script>
@endsection
