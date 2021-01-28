@extends('layouts.app')
@section('title', 'Send New SMS')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Send SMS</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Send New SMS</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Send New SMS</div>
                <div class="card-body">
                    <form method="post" action="{{ route('sms.create') }}">
                        @csrf

                        <div class="form-group">
                            <label>To Customer</label>
                            <select class="select2 form-control" name="customer">
                                <option value="">Customer</option>
                                @foreach(auth()->user()->customers()->get() as $c)
                                    <option value="{{ $c->id }}" @if ($request->customer == $c->id) selected @endif>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">SMS Content ([[ chars_left ]]/[[ max ]] chars)</label>
                            <textarea name="body" class="form-control" placeholder="SMS Content" v-model="sms" maxlength="160" rows="6"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Send SMS</button>
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
    <script src="https://unpkg.com/vue@next"></script>
    <script>
        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    sms: '',
                    max: 160,
                }
            },
            methods: {
            },
            computed: {
                chars_left() {
                    return this.max - this.sms.length;
                }
            }
        }

        Vue.createApp(App).mount('#vue');
        $('.select2').select2();
    </script>
@endsection
