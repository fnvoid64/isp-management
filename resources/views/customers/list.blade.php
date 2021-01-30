@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Customers</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customers</li>
                </ol>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('customers.create') }}">
                <button class="btn btn-success float-right">Add Customer</button>
            </a>
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
                            Customer List
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.status">
                                <option value="">Status</option>
                                <option value="{{ \App\Models\Customer::STATUS_ACTIVE }}">Active</option>
                                <option value="{{ \App\Models\Customer::STATUS_PENDING }}">Pending</option>
                                <option value="{{ \App\Models\Customer::STATUS_DISABLED }}">Disabled</option>
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.area">
                                <option value="">Area</option>
                                @php $user = auth()->user() @endphp
                                @foreach($user->areas()->get() as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.connectionPoint">
                                <option value="">Connection Point</option>
                                @foreach($user->connection_points()->get() as $connection_point)
                                    <option value="{{ $connection_point->id }}">{{ $connection_point->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.package">
                                <option value="">Package</option>
                                @foreach($user->packages()->get() as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
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
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Dues</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>#[[ item.id ]]</td>
                                    <td>[[ item.name ]]</td>
                                    <td>0[[ item.mobile ]]</td>
                                    <td>[[ item.address ]]</td>
                                    <td>
                                        <span class="badge badge-success" v-if="item.status === {{ \App\Models\Customer::STATUS_ACTIVE }}">Active</span>
                                        <span class="badge badge-warning" v-if="item.status === {{ \App\Models\Customer::STATUS_PENDING }}">Pending</span>
                                        <span class="badge badge-danger" v-if="item.status === {{ \App\Models\Customer::STATUS_DISABLED }}">Disabled</span>
                                    </td>
                                    <td>[[ item.dues ]]</td>
                                    <td>
                                        <a :href="`/dashboard/customers/${item.id}`">
                                            <button class="btn btn-success btn-sm">View</button>
                                        </a>
                                        <a :href="`/dashboard/customers/${item.id}/edit`">
                                            <button class="btn btn-primary btn-sm ml-1">Edit</button>
                                        </a>
                                        <button class="btn btn-danger btn-sm ml-1" @click="deleteItem(item.id, item.name)">Delete</button>
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
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/vue-router@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        const url = '{{ route('customers') }}';
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
                            router.push({ path: 'customers', query: this.filters});
                        }

                        this.pageLoading = false;
                    }).catch(function (error) {
                        //console.log(error);
                        this.pageLoading = false;
                    });
                },
                deleteItem(id, name) {
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'danger',
                        html:`<form method="post" action="/dashboard/customers/${id}/delete">
                            @csrf
                            @method('DELETE')
                            <p>Are you sure you want to delete customer ${name}?</p>
                        <div class="form-group">
                        <input type="number" name="pin" class="form-control" placeholder="PIN" required>
                        </div>
                        <div class="form-group">
                        <button class="btn btn-danger btn-block">Delete</button>
                        </div>
                        </form>`,
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        showConfirmButton: false
                    })
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

@section('styles')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection
