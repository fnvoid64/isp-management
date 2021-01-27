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
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Paid By</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>[[ item.id ]]</td>
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
                                        <a :href="`/dashboard/customers/${item.customer.id}`" v-if="item.employee_id">[[ item.customer.name ]]</a>
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
                        type: '{{ $request->type }}',
                        customer: '{{ $request->customer }}',
                        employee: '{{ $request->employee }}',
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

        Vue.createApp(App).use(router).mount('#vue');
    </script>
@endsection
