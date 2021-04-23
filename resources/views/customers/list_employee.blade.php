@extends('layouts.employee')
@section('title', 'Customers')

@section('content')
    <div style="display: flex;justify-content: space-between;align-items: center;">
        <h5>Customers</h5>
        <a href="{{ route('employee_customers.create') }}">
            <button class="btn btn-success float-right btn-small">Apply New Customer</button>
        </a>
    </div>

    @include('includes.messages')
    @include('includes.errors')

    
    <div class="row">
        <div class="card">
            <div class="col s12 center-align" style="padding: 10px">
                <button class="btn btn-small grey" @click="filtersOpen = !filtersOpen">
                [[ filtersOpen ? 'Close' : 'Open' ]] Filters
                </button>
            </div>
            <div class="col s12" v-show="filtersOpen">
                <div class="col s12">
                    <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div class="input-field col s6">
                    <select v-model="filters.status">
                        <option value="">Status</option>
                        <option value="{{ \App\Models\Customer::STATUS_ACTIVE }}">Active</option>
                        <option value="{{ \App\Models\Customer::STATUS_PENDING }}">Pending</option>
                        <option value="{{ \App\Models\Customer::STATUS_DISABLED }}">Disabled</option>
                    </select>
                </div>

                <div class="input-field col s6">
                    <select v-model="filters.area">
                        <option value="">Area</option>
                        @php $user = \App\Models\Employee::getEmployee()->user @endphp
                        @foreach($user->areas()->get() as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s6">
                    <select v-model="filters.connectionPoint">
                        <option value="">Connection Point</option>
                        @foreach($user->connection_points()->get() as $connection_point)
                            <option value="{{ $connection_point->id }}">{{ $connection_point->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s6">
                    <select v-model="filters.package">
                        <option value="">Package</option>
                        @foreach($user->packages()->get() as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'BroadBand' : 'Cable Tv' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s12">
                    <input type="text" name="search" placeholder="Search.." v-model="filters.searchQuery">
                </div>
            </div>
                <div>
                    <div v-if="items && items.total !== 0">
                        <div class="collection">
                            <a :href="`/dashboard_v2/customers/${item.id}`" class="collection-item grey-text text-darken-2" v-for="item in items.data" :key="item.id">
                                #[[ item.id ]]. <b>[[ item.name ]]</b>
                                <span :class="`new badge ${status[item.status].class}`" data-badge-caption="">[[ status[item.status].name ]]</span>
                                <br/>
                                <small>[[ item.address ]]</small><br/>
                                <b>0[[ item.mobile ]]</b> (Due: <b>BDT [[ item.dues ]]</b>)
                            </a>
                        </div>

                        <div class="row" style="align-items: center">
                            <div class="col s4" v-if="items.prev_page_url">
                                <button class="btn btn-small left" @click="filters.page -= 1">Prev</button>
                            </div>

                            <div class="col s4 center">
                                Showing Page [[ items.current_page ]] of [[ items.last_page ]]
                            </div>

                            <div class="col s4" v-if="items.next_page_url">
                                <button class="btn btn-small right" @click="filters.page += 1">Next</button>
                            </div>
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
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/vue-router@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        const url = '{{ route('employee_customers') }}';
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
                        area: '{{ $request->area }}',
                        connectionPoint: '{{ $request->connectionPoint }}',
                        package: '{{ $request->package }}',
                        searchQuery: '{{ $request->searchQuery }}',
                        page: {{ $request->page ?? 1 }},
                    },
                    pageLoading: false,
                    filtersOpen: false,
                    status: [{class: 'red', name: 'Disabled'},{class: 'green', name: 'Active'},{class: 'grey', name: 'Pending'}]
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
                            router.push({ path: 'customers', query: this.filters});
                        }

                        this.pageLoading = false;
                    }).catch(function (error) {
                        //console.log(error);
                        this.pageLoading = false;
                    });
                },
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
