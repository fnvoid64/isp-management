@extends('layouts.app')
@section('title', 'নতুন গ্রাহক যোগ')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">গ্রাহক</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers') }}">গ্রাহক</a></li>
                    <li class="breadcrumb-item active">নতুন গ্রাহক যোগ</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">নতুন গ্রাহক যোগ</div>
                <div class="card-body">
                    <form method="post" action="{{ route('customers.create') }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="name">পুরো নাম</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Customer Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="f_name">বাবার নাম</label>
                                    <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}" placeholder="Customer Father's Name">
                                </div>
                                <div class="form-group">
                                    <label for="m_name">মাতার নাম</label>
                                    <input type="text" name="m_name" class="form-control" value="{{ old('m_name') }}" placeholder="Customer Mother's Name">
                                </div>
                                <div class="form-group">
                                    <label for="mobile">মোবাইল নম্বর</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="Customer Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label for="nid">জাতীয় পরিচয়পত্রের নম্বর</label>
                                    <input type="number" name="nid" class="form-control" value="{{ old('nid') }}" placeholder="Customer NID Number">
                                </div>
                                <div class="form-group">
                                    <label for="address">ঠিকানা</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Customer Address">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="area">এলাকা</label>
                                    @php $user = auth()->user(); @endphp
                                    <select name="area" class="select2 form-control" data-placeholder="এলাকা সিলেক্ট করুন">
                                        <option value="">এলাকা সিলেক্ট করুন</option>
                                        @foreach($user->areas()->get() as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="area">সংযোগস্থল</label>
                                    <select name="connection_point" class="select2 form-control" data-placeholder="সংযোগস্থল সিলেক্ট করুন">
                                        <option value="">সংযোগস্থল সিলেক্ট করুন</option>
                                        @foreach($user->connection_points()->get() as $connection_point)
                                            <option value="{{ $connection_point->id }}">{{ $connection_point->name }} ({{ $connection_point->area->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="package[]">প্যাকেজ</label>
                                    <select name="package[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="প্যাকেজ সিলেক্ট করুন ">
                                        @foreach($user->packages()->get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->type == \App\Models\Package::TYPE_BROADBAND ? 'ব্রডব্যান্ড' : 'ক্যাবল টিভি' }}) [{{ $package->sale_price }} টাকা]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>ব্রডব্যান্ড এর ক্ষেত্রে ইউজারনেম</label>
                                    <input type="text" name="net_user" class="form-control" value="{{ old('net_user') }}" placeholder="Broadband PPPoE/Other Username">
                                </div>
                                <div class="form-group">
                                    <label>ব্রডব্যান্ড এর ক্ষেত্রে পাসওয়ার্ড</label>
                                    <input type="text" name="net_pass" class="form-control" value="{{ old('net_pass') }}" placeholder="Broadband PPPoE/Other Password">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h4 class="my-2">
                                    ইনভয়েস তৈরি (ইচ্ছাকৃত)
                                </h4>
                                <div class="form-group">
                                    <label>ইনভয়েসএর পরিমাণ (টাকায়)</label>
                                    <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="Invoice Amount">
                                </div>
                                <div class="form-group">
                                    <label>ইনভয়েস টা কিসের সেটার বিবরণ</label>
                                    <input type="text" name="comment" class="form-control" value="{{ old('comment') }}" placeholder="Invoice Comment">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-block" type="submit">গ্রাহক তৈরী করুন</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection
