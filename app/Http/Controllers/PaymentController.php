<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return view('payments.list', ['request' => $request]);
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();
            $payments = $user
                ->payments()
                ->select(['id', 'amount', 'customer_id', 'type', 'employee_id']);

            if ($request->filled('customer')) {
                $customer = $user->customers()->findOrFail($request->customer);
                $payments = $customer->payments();
            }

            if ($request->filled('employee')) {
                $employee = $user->employees()->findOrFail($request->employee);
                $payments = $employee->payments();
            }

            if ($request->filled('searchQuery') && $request->searchQuery != '#') {
                $request->searchQuery = ltrim($request->searchQuery, '#');
                $payments->where('id', 'ilike', '%' . $request->searchQuery . '%');
            }

            if ($request->filled('type')) {
                $payments = $payments->where('type', $request->type);
            }

            $payments = $payments
                ->orderBy('id', 'DESC')
                ->with('customer:id,name')
                ->with('employee:id,name')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            return $payments;
        }

        return false;
    }

    public function show(Payment $payment)
    {
        //
    }

    public function printOut(Payment $payment)
    {
        return view('payments.print', ['payment' => $payment]);
    }
}
