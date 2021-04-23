@extends('layouts.employee')
@section('title', 'Printers')

@section('content')
    <div style="display:flex; justify-content:space-between;align-items:center">
        <h5>Printers</h5>
        @php $em = App\Models\Employee::getEmployee(); @endphp
        <a href="cmsd://print_receipt?data={{ base64_encode(json_encode(['func' => 'add', 'payload' => ['user_id' =>  $em->user_id, 'employee_id' => $em->id]])) }}" class="btn">
            Add Printer
        </a>
    </div>

    @include('includes.messages')
    @include('includes.errors')

<div class="row">
    <div class="card">
        <div class="collection">
            @forelse($printers as $printer)
            <div class="collection-item">
                <i class="material-icons">print</i> {{ $printer->name }}<br/>
                {{ $printer->address }}
            </div>
            @empty
            <div class="collection-item">No printer found!</div>
            @endforelse
        </div>
    </div>
</div>
@endsection