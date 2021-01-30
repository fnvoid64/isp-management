@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Edit Profile</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Profile</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Edit Profile</div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Full Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" placeholder="Email" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Father's Name</label>
                            <input type="text" name="f_name" class="form-control" value="{{ $user->f_name }}" placeholder="Father's Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Mother's Name</label>
                            <input type="text" name="m_name" class="form-control" value="{{ $user->m_name }}" placeholder="Mother's Name">
                        </div>
                        <div class="form-group">
                            <label for="name">NID</label>
                            <input type="text" class="form-control" value="{{ $user->nid }}" placeholder="NID" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Mobile</label>
                            <input type="text" name="mobile" class="form-control" value="0{{ $user->mobile }}" placeholder="Mobile Number">
                        </div>
                        <div class="form-group">
                            <label for="name">Address</label>
                            <textarea name="address" class="form-control" placeholder="Address">{{ $user->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $user->company_name }}" placeholder="Company name">
                        </div>
                        <div class="form-group">
                            <label for="name">Company Short Name</label>
                            <input type="text" name="company_short" class="form-control" value="{{ $user->company_short }}" placeholder="Company short name" maxlength="5">
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-2">
                                    <img :src="profile_photo" alt="" v-if="profile_photo" class="rounded-circle img-fluid">
                                    <img src="{{ $user->photo ? asset('/storage/' . $user->photo) : '/assets/images/profile-min.png' }}" alt="" v-else="profile_photo" class="rounded-circle img-fluid">
                                </div>
                                <div class="col-10">
                                    <label>Profile Picture</label>
                                    <input type="file" name="photo" class="form-control" @change="onFileChange">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/vue@next"></script>
    <script>
        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    profile_photo: null
                }
            },
            methods: {
                onFileChange(e) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0]);
                },
                createImage(file) {
                    var profile_photo = new Image();
                    var reader = new FileReader();
                    var vm = this;

                    reader.onload = (e) => {
                        vm.profile_photo = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                removeImage: function (e) {
                    this.profile_photo = null;
                }
            }
        }

        Vue.createApp(App).mount('#vue');
    </script>
@endsection
