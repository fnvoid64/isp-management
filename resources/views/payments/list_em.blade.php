@extends('layouts.employee')
@section('title', 'Payments')

@section('content')
    <h5>Payments</h5>

    @include('includes.messages')
    @include('includes.errors')

<div class="row">
    <div class="card">
        <div class="col s12 center">
                <div class="col s12">
                    <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div class="col s6">
                    <button type="button" class="btn btn-secondary btn-sm" id="daterange-btn">
                        <i class="ti-calendar"></i> <span>Select Date</span>
                        <i class="ti-arrow-down"></i>
                    </button>
                </div>

                <div class="col s6">
                    <input type="text" class="form-control form-control-sm"
                            placeholder="Search.." v-model="filters.searchQuery">
                </div>
            </div>
        </div>

        <div v-if="items && items.total !== 0">
            <div class="collection">
                <div v-for="item in items.data" :key="item.id" class="collection-item grey-text text-darken-2">
                    Payment #[[ item.id ]]. Invoices ([[ item.invoices_paid ]])
                    <span :class="`new badge ${status[item.status].class}`" data-badge-caption="">[[ status[item.status].name ]]</span>

                    <br/>
                    Customer: <a :href="`/dashboard_v2/customers/${item.customer.id}`">[[ item.customer.name ]]</a>
                    <br/>Amount: <b>BDT [[ item.amount ]]</b> Method: <span v-if="item.type == {{ \App\Models\Payment::TYPE_CASH }}">Cash</span>
                            <span v-else-if="item.type == {{ \App\Models\Payment::TYPE_MOBILE_BANK }}">bKash/Rocket/Nagad Etc</span>
                            <span v-else-if="item.type == {{ \App\Models\Payment::TYPE_BANK }}">Bank</span><br/>
                    <span>Collected By: <b>[[ item.employee_id ? item.employee.name : '{{ \App\Models\Employee::getEmployee()->user->name }} (Admin)' ]]</b></span>
                    <br/>
                    <div style="display: flex; justify-content: space-between; align-items:center;margin-top: 8px">
                    <a :href="`/dashboard_v2/payments/${item.id}`">
                        <button class="btn btn-primary btn-small ml-1">View</button>
                    </a>
                    <a :href="`/dashboard_v2/payments/${item.id}/print`">
                        <button class="btn btn-success btn-small ml-1">Print</button>
                    </a></div>
                </div>
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
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/vue-router@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const url = '{{ route('employee_payments') }}';
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
                        type: '{{ $request->type }}',
                        customer: '{{ $request->customer }}',
                        employee: '{{ $request->employee }}',
                        searchQuery: '{{ $request->searchQuery }}',
                        date: '{{ $request->date }}',
                        page: {{ $request->page ?? 1 }},
                    },
                    pageLoading: false,
                    status: [{class: 'red', name: 'Rejected'},{class: 'green', name: 'Confirmed'},{class: 'grey', name: 'Unconfirmed'}]
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
