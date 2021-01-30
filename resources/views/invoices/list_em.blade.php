@extends('layouts.employee')
@section('title', 'Invoices')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Invoices</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard_v2') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Invoices</li>
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
                    <div class="row">
                        <div class="col">
                            <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Invoice List
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.status">
                                <option value="">Status</option>
                                <option value="{{ \App\Models\Invoice::STATUS_PAID }}">Paid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_UNPAID }}">Unpaid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_PARTIAL_PAID }}">Partially Paid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_CANCELLED }}">Cancelled</option>
                            </select>
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
                                <th>ID</th>
                                <th>Month</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>[[ item.id ]]</td>
                                    <td>[[ (new Date(Date.parse(item.created_at))).toLocaleString('default', { month: 'long' }) ]]</td>
                                    <td>
                                        <a :href="`/dashboard_v2/customers/${item.customer.id}`">[[ item.customer.name ]]</a>
                                    </td>
                                    <td>BDT [[ item.amount ]]</td>
                                    <td>BDT [[ item.due ]]</td>
                                    <td>
                                        <span class="badge badge-success" v-if="item.status == {{ \App\Models\Invoice::STATUS_PAID }}">Paid</span>
                                        <span class="badge badge-primary" v-else-if="item.status == {{ \App\Models\Invoice::STATUS_PARTIAL_PAID }}">Partially Paid</span>
                                        <span class="badge badge-danger" v-else-if="item.status == {{ \App\Models\Invoice::STATUS_UNPAID }}">Unpaid</span>
                                        <span class="badge badge-secondary" v-else>Cancelled</span>
                                    </td>
                                    <td>
                                        <a :href="`/dashboard_v2/invoices/${item.id}`">
                                            <button class="btn btn-primary btn-sm ml-1">View</button>
                                        </a>
                                        <a :href="`/dashboard_v2/invoices/${item.id}/payEm`" v-if="item.due > 0">
                                            <button class="btn btn-success btn-sm ml-1">Pay</button>
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
        const url = '{{ route('employee_invoices') }}';
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
                        date: '{{ $request->date }}',
                        status: '{{ $request->status }}',
                        searchQuery: '{{ $request->searchQuery }}',
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
                            router.push({ path: 'invoices', query: this.filters});
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
