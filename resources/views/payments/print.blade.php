<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment #{{ $payment->id }}</title>
    <style>
        .header {
            text-align: center;
            margin-bottom: 6px;
        }
        .logo {
            font-weight: bold;
            font-size: 1.2em;
        }
        .info {
            font-size: small;
        }
        .content {

        }
        .line {
            display: flex;
            align-content: center;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .label {

        }
        .value {

        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ ($user = $employee ? $employee->user : auth()->user())->company_name }}</div>
        <div class="info">{{ $user->address }} <b>Mobile:</b> 0{{ $user->mobile }}</div>
    </div>
    <div class="content">
        <div class="line">
            <div class="label">Customer</div>
            <div class="value">{{ $payment->customer->name }}</div>
        </div>
        <div class="line">
            <div class="label">Amount</div>
            <div class="value">BDT {{ number_format($payment->amount, 2) }}</div>
        </div>
        <div class="line">
            <div class="label">Payment Method</div>
            <div class="value">
                @if ($payment->type == \App\Models\Payment::TYPE_BANK)
                    Bank
                @elseif ($payment->type == \App\Models\Payment::TYPE_MOBILE_BANK)
                    bKash/Rocket/Nagad
                @else
                    Cash
                @endif
            </div>
        </div>
        <div class="line">
            <div class="label">Payment For</div>
            <div class="value">
                @foreach($payment->invoices()->select(['invoices.id', 'invoices.status', 'invoices.created_at'])->get() as $invoice)
                    Invoice #{{ $invoice->id }} ({{ $invoice->created_at->format('F') }}) @if ($invoice->status == \App\Models\Invoice::STATUS_PARTIAL_PAID) [Partial] @endif
                @endforeach
            </div>
        </div>
        <div class="line">
            <div class="label">Collected By</div>
            <div class="value">
                @if ($payment->employee_id)
                    {{ $payment->employee->name }}
                @else
                    {{ $user->name }} (Admin)
                @endif
            </div>
        </div>
    </div>
<script>
    window.print();
</script>
</body>
</html>
