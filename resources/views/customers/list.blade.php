@extends('layouts.app')
@section('title', 'Customers')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">গ্রাহক</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">গ্রাহক</li>
                </ol>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('customers.create') }}">
                <button class="btn btn-success float-right">গ্রাহক যোগ করুন</button>
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
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="spinner-grow spinner-grow-sm text-success mr-1" role="status" v-if="pageLoading">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    গ্রাহক তালিকা
                                    <span v-if="items">(মোট = [[ items.total ]])</span>
                                    <button class="btn btn-dark btn-sm ml-2" v-if="items && items.total > 0" @click="exportPDF">পিডিএফ লিস্ট নামান</button>
                                </div>
                                <div class="col-auto" v-if="items" style="display: flex;justify-content: space-evenly; align-items: center">
                                    <span style="flex: 3">প্রতি পেজে</span>
                                    <select class="form-control form-control-sm" style="flex: 2" v-model="filters.per_page">
                                        <option value="20">২০</option>
                                        <option value="50">৫০</option>
                                        <option value="100">১০০</option>
                                        <option :value="items.total">সব ([[ items.total ]])</option>
                                    </select>
                                    <span class="ml-1" style="flex: auto">টি গ্রাহক</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            ফিল্টার:
                        </div>
                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.status">
                                <option value="">সব অবস্থা</option>
                                <option value="{{ \App\Models\Customer::STATUS_ACTIVE }}">সক্রিয়</option>
                                <option value="{{ \App\Models\Customer::STATUS_PENDING }}">অপেক্ষমান</option>
                                <option value="{{ \App\Models\Customer::STATUS_DISABLED }}">বন্ধ</option>
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.area">
                                <option value="">সব এলাকা</option>
                                @php $user = auth()->user() @endphp
                                @foreach($user->areas()->get() as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.connectionPoint">
                                <option value="">সব সংযোগস্থল</option>
                                @foreach($user->connection_points()->get() as $connection_point)
                                    <option value="{{ $connection_point->id }}">{{ $connection_point->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control form-control-sm" v-model="filters.package">
                                <option value="">সব প্যাকেজ</option>
                                @foreach($user->packages()->get() as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'ব্রডব্যান্ড' : 'ক্যাবল টিভি' }}) [{{ $package->sale_price }} টাকা]</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="খুজুন.." v-model="filters.searchQuery">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="items && items.total !== 0">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table">
                                <thead>
                                <th>#ID</th>
                                <th>নাম</th>
                                <th>মোবাইল নম্বর</th>
                                <th>ঠিকানা</th>
                                <th>অবস্থা</th>
                                <th>বাকি</th>
                                <th>অপশন</th>
                                </thead>
                                <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>#[[ item.id ]]</td>
                                    <td>[[ item.name ]]</td>
                                    <td>0[[ item.mobile ]]</td>
                                    <td>[[ item.address ]]</td>
                                    <td>
                                        <span class="badge badge-success" v-if="item.status === {{ \App\Models\Customer::STATUS_ACTIVE }}">সক্রিয়</span>
                                        <span class="badge badge-warning" v-if="item.status === {{ \App\Models\Customer::STATUS_PENDING }}">অপেক্ষমান</span>
                                        <span class="badge badge-danger" v-if="item.status === {{ \App\Models\Customer::STATUS_DISABLED }}">বন্ধ</span>
                                    </td>
                                    <td>[[ item.dues ]] টাকা</td>
                                    <td>
                                        <a :href="`/dashboard/customers/${item.id}`">
                                            <button class="btn btn-success btn-sm">দেখুন</button>
                                        </a>
                                        <a :href="`/dashboard/customers/${item.id}/edit`">
                                            <button class="btn btn-primary btn-sm ml-1">পরিবর্তন</button>
                                        </a>
                                        <button class="btn btn-danger btn-sm ml-1" @click="deleteItem(item.id, item.name)">মুছুন</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm" v-if="items.prev_page_url">
                                <button class="btn btn-primary float-left" @click="filters.page -= 1">আগের পেজ</button>
                            </div>

                            <div class="col-sm">
                                পেজ নম্বর [[ items.current_page ]] দেখানো হচ্ছে [[ items.last_page ]] পেজের মধ্যে
                            </div>

                            <div class="col-sm" v-if="items.next_page_url">
                                <button class="btn btn-primary float-right" @click="filters.page += 1">পরের পেজ</button>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        কোনো তথ্য পাওয়া যায়নি।
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.4/jspdf.plugin.autotable.min.js"></script>

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
                        per_page: {{ $request->per_page ?? 20 }},
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
                        title: 'আপনি নিশ্চিত?',
                        icon: 'danger',
                        html:`<form method="post" action="/dashboard/customers/${id}/delete">
                            @csrf
                            @method('DELETE')
                            <p>আপনি কি নিশ্চিত যে আপনি এই গ্রাহক ${name} কে মুছে ফেলতে চান?</p>
                        <div class="form-group">
                        <input type="number" name="pin" class="form-control" placeholder="PIN" required>
                        </div>
                        <div class="form-group">
                        <button class="btn btn-danger btn-block">হ্যা মুছে ফেলুন</button>
                        </div>
                        </form>`,
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        showConfirmButton: false
                    })
                },
                exportPDF() {
                    var vm = this
                    var columns = [
                        {title: "#ID", dataKey: "id"},
                        {title: "Name", dataKey: "name"},
                        {title: "Mobile", dataKey: "mobile"},
                        {title: "Address", dataKey: "address"},
                        {title: "Status", dataKey: "status"},
                        {title: "Dues", dataKey: "dues"},
                    ];
                    var doc = new jsPDF('p', 'pt');
                    doc.text('Customer List', 40, 40);
                    doc.autoTable(columns, vm.items.data.map(function (e) {
                        if (e.status === 1) {
                            e.status = 'Active';
                        } else if(e.status === 2) {
                            e.status = 'Pending';
                        } else {
                            e.status = 'Disabled';
                        }

                        e.mobile = `0${e.mobile}`;
                        return e;
                    }), {
                        margin: {top: 60},
                    });
                    doc.save('customers.pdf');
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
