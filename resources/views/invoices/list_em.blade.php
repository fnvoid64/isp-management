@extends('layouts.employee')
@section('title', 'Invoices')

@section('styles')
<style>
.openModal {
    z-index: 1003;
    display: block;
    opacity: 1;
    top: 10%;
    transform: scaleX(1) scaleY(1);
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <h5>Invoices</h5>

    @include('includes.messages')
    @include('includes.errors')

    <div class="row">

            <div class="card">
                <div class="col s12">
                    <div class="row">
                        <div class="col s12">
                            <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="col s6">
                            <select v-model="filters.status">
                                <option value="">Status</option>
                                <option value="{{ \App\Models\Invoice::STATUS_PAID }}">Paid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_UNPAID }}">Unpaid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_PARTIAL_PAID }}">Partially Paid</option>
                                <option value="{{ \App\Models\Invoice::STATUS_CANCELLED }}">Cancelled</option>
                            </select>
                        </div>

                        <div class="col s6">
                            <button type="button" class="btn btn-secondary btn-small" id="daterange-btn">
                                <i class="ti-calendar"></i> <span>Select Date</span>
                                <i class="ti-arrow-down"></i>
                            </button>
                        </div>
                        <div class="col s12">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="Search.." v-model="filters.searchQuery">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="items && items.total !== 0">
                        <div class="collection">
                            <div v-for="item in items.data" :key="item.id" class="collection-item grey-text text-darken-2">
                                Invoice #[[ item.id ]]. [[ (new Date(Date.parse(item.created_at))).toLocaleString('default', { month: 'long' }) ]]
                                <span :class="`new badge ${status[item.status].class}`" data-badge-caption="">[[ status[item.status].name ]]</span>
                                <br/>
                                Customer: <a :href="`/dashboard_v2/customers/${item.customer.id}`">[[ item.customer.name ]]</a>
                                <br/>Amount: <b>BDT [[ item.amount ]]</b> Due: <b class="red-text">BDT [[ item.due ]]</b><br/>
                                <div style="display: flex; justify-content: space-between; align-items:center;margin-top: 8px">
                                    <a :href="`/dashboard_v2/invoices/${item.id}`">
                                        <button class="btn btn-primary btn-small ml-1">View</button>
                                    </a>
                                    <button class="btn btn-small" v-if="item.due > 0" @click="openPayModal(item)">
                                        Pay
                                    </button>
                                </div>
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

                        <div class="modal" :class="{openModal: isOpenPayModal}">
                            <div class="modal-content">
                                <h4>Make Payment</h4>

                                <form method="post" :action="`/dashboard_v2/invoices/${openedItem?.id}/pay`">
                                        @csrf
                                        <div>
                                            <label for="amount">Payment Amount</label>
                                            <input type="number" class="form-control" name="amount" placeholder="Payment Amount" required>
                                        </div>
                                        <div>
                                            <select name="type" class="browser-default">
                                                <option value="{{ \App\Models\Payment::TYPE_CASH }}">Cash</option>
                                                <option value="{{ \App\Models\Payment::TYPE_MOBILE_BANK }}">bKash/Rocket/Nagad Etc</option>
                                                <option value="{{ \App\Models\Payment::TYPE_BANK }}">Bank</option>
                                            </select>
                                        </div>
                                        <button class="btn" type="submit">Pay</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn grey" type="button" @click="isOpenPayModal = false">Close</button>
                                        
                                    </div>
                                </form>
                                </div>
                            </div>
                    <div v-else>
                        No result found in database!
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
                    openedItem: null,
                    isOpenPayModal: false,
                    filters: {
                        date: '{{ $request->date }}',
                        status: '{{ $request->status }}',
                        searchQuery: '{{ $request->searchQuery }}',
                        page: {{ $request->page ?? 1 }},
                    },
                    pageLoading: false,
                    status: [{class: 'grey', name: 'Cancelled'},{class: 'green', name: 'Paid'},{class: 'red', name: 'Unpaid'},{class: 'blue', name: 'Partially Paid'}]
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
                },
                openPayModal(item) {
                    this.openedItem = item;
                    this.isOpenPayModal = true;
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
                if (start != undefined && end != undefined) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    vapp.filters.date = start.format('Y-M-D') + ':' + end.format('Y-M-D');
                }
                
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
