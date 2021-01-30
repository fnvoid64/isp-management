@extends('layouts.app')
@section('title', 'Payments')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Payments</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payments</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Payments List
                        </div>

                        <div class="col-auto">
                            <button type="button" class="btn btn-secondary btn-sm" id="daterange-btn">
                                <i class="ti-calendar"></i> <span>Select Date</span>
                                <i class="ti-arrow-down"></i>
                            </button>
                        </div>

                        <div class="col-auto">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Search.." v-model="filters.searchQuery">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="items && items.total !== 0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <th>#ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Paid By</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>#[[ item.id ]]</td>
                                    <td>
                                        <a :href="`/dashboard/customers/${item.customer.id}`">[[ item.customer.name ]]</a>
                                    </td>
                                    <td>BDT [[ item.amount ]]</td>
                                    <td>
                                        <span v-if="item.type == {{ \App\Models\Payment::TYPE_CASH }}">Cash</span>
                                        <span v-else-if="item.type == {{ \App\Models\Payment::TYPE_MOBILE_BANK }}">bKash/Rocket/Nagad Etc</span>
                                        <span v-else-if="item.type == {{ \App\Models\Payment::TYPE_BANK }}">Bank</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success" v-if="item.status == {{ \App\Models\Payment::STATUS_CONFIRMED }}">Confirmed</span>
                                        <span class="badge badge-primary" v-else-if="item.status == {{ \App\Models\Payment::STATUS_PENDING }}">Pending</span>
                                        <span class="badge badge-danger" v-else-if="item.status == {{ \App\Models\Payment::STATUS_REJECTED }}">Rejected</span>
                                    </td>
                                    <td>
                                        <a :href="`/dashboard/employees/${item.employee.id}`" v-if="item.employee_id">[[ item.employee.name ]]</a>
                                        <span v-else>{{ auth()->user()->name }} (Admin)</span>
                                    </td>
                                    <td>
                                        <a :href="`/dashboard/payments/${item.id}`">
                                            <button class="btn btn-primary btn-sm ml-1">View</button>
                                        </a>
                                        <a :href="`/dashboard/payments/${item.id}/print`">
                                            <button class="btn btn-success btn-sm ml-1">Print</button>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm" v-if="items.prev_page_url">
                                <button class="btn btn-primary float-left" @click="filters.page -= 1">Prev</button>
                            </div>

                            <div class="col-sm">
                                Showing Page [[ items.current_page ]] of [[ items.last_page ]]
                            </div>

                            <div class="col-sm" v-if="items.next_page_url">
                                <button class="btn btn-primary float-right" @click="filters.page += 1">Next</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        No result found in database!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/vue-router@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const url = '{{ route('payments') }}';
        const router = VueRouter.createRouter({
            history: VueRouter.createWebHistory(),
            routes: [],
        });

        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    items: null,
                    filters: {
                        status: '{{ $request->status }}',
                        type: '{{ $request->type }}',
                        customer: '{{ $request->customer }}',
                        employee: '{{ $request->employee }}',
                        searchQuery: '{{ $request->searchQuery }}',
                        date: '{{ $request->date }}',
                        page: {{ $request->page ?? 1 }},
                    },
                    pageLoading: false
                }
            },
            mounted() {
                this.getData(false);
            },
            methods: {
                getData(update = true) {
                    this.pageLoading = true;
                    axios.post(url, this.filters).then(response => {
                        this.items = response.data;
                        if (update) {
                            router.push({ path: 'payments', query: this.filters});
                        }

                        this.pageLoading = false;
                    }).catch(function (error) {
                        //console.log(error);
                        this.pageLoading = false;
                    });
                }
            },
            watch: {
                filters: {
                    deep: true,
                    handler(val) {
                        this.getData()
                    }
                }
            }
        }

        vapp = Vue.createApp(App).use(router).mount('#vue');
        $(function() {

            var start = undefined;
            var end = undefined;

            function cb(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                vapp.filters.date = start.format('Y-M-D') + ':' + end.format('Y-M-D');
            }

            $('#daterange-btn').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

