@extends('layouts.app')
@section('title', 'Edit Package')

@section('content')
    <div class="row align-items-center">
        <div class="col">
            <div class="page-title-box">
                <h4 class="font-size-18">Packages</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('packages') }}">Packages</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('packages.show', ['package' => $package->id]) }}">{{ $package->name }}</a></li>
                    <li class="breadcrumb-item active">Edit Package</li>
                </ol>
            </div>
        </div>
    </div>
    @include('includes.messages')
    @include('includes.errors')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Edit Package</div>
                <div class="card-body">
                    <form method="post" action="{{ route('packages.edit', ['package' => $package->id]) }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $package->name }}" placeholder="Package Name">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="">Select Package Type</option>
                                <option value="{{ \App\Models\Package::TYPE_BROADBAND }}" @if ($package->type == \App\Models\Package::TYPE_BROADBAND) selected @endif>Broadband</option>
                                <option value="{{ \App\Models\Package::TYPE_CABLE_TV }}" @if ($package->type == \App\Models\Package::TYPE_CABLE_TV) selected @endif>Cable TV</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Buying Price</label>
                            <input type="number" name="buying_price" class="form-control" value="{{ $package->buying_price }}" placeholder="Package Buying Price">
                        </div>
                        <div class="form-group">
                            <label>Sale Price</label>
                            <input type="number" name="sale_price" class="form-control" value="{{ $package->sale_price }}" placeholder="Package Sale Price">
                        </div>
                        <div class="form-group">
                            <div style="display: flex; align-content: center;">
                                <input type="checkbox" id="switch3" name="on_site" switch="bool" @if ($package->on_site) checked @endif>
                                Show on site? <label for="switch3" data-on-label="Yes" data-off-label="No" class="ml-2"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-block" type="submit">Edit Package</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
