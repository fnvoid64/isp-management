<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return view('payments.list', ['request' => $request]);
    }

    public function indexEm(Request $request)
    {
        return view('payments.list_em', ['request' => $request]);
    }

    public function indexData(Request $request, $employee = null)
    {
        if ($request->expectsJson()) {
            $user = $employee ? $employee->user : auth()->user();
            $payments = $user
                ->payments()
                ->select(['id', 'amount', 'customer_id', 'type', 'employee_id', 'status']);

            if ($request->filled('customer')) {
                $customer = $user->customers()->findOrFail($request->customer);
                $payments = $customer->payments();
            }

            if ($request->filled('employee')) {
                $employee2 = $user->employees()->findOrFail($request->employee);
                $payments = $employee2->payments();
            }

            if ($request->filled('searchQuery') && $request->searchQuery != '#') {
                $request->searchQuery = ltrim($request->searchQuery, '#');
                $payments->where('id', 'ilike', '%' . $request->searchQuery . '%');
            }

            if ($request->filled('type')) {
                $payments = $payments->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $payments = $payments->where('status', $request->status);
            }

            if ($request->filled('date')) {
                $date = explode(':', $request->date);

                $payments = $payments->whereBetween('created_at', $date);
            }

            $payments = $payments
                ->orderBy('id', 'DESC')
                ->with('customer:id,name')
                ->with('employee:id,name')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            $payments->data = $payments->each(function ($p) {
                $invoices_paid = [];

                foreach ($p->invoices()->select(['invoices.id', 'invoices.created_at'])->get() as $invoice) {
                    $invoices_paid[] = "#$invoice->id (" . $invoice->created_at->format('F') . ")";
                }
                $p->invoices_paid = implode('<br/>', $invoices_paid);
            });

            return $payments;
        }

        return false;
    }

    public function indexDataEm(Request $request)
    {
        return $this->indexData($request, Employee::getEmployee());
    }

    public function show(Payment $payment)
    {
        $user = auth()->user();

        if ($payment->user_id != $user->id) {
            abort(404);
        }

        return view('payments.show', ['payment' => $payment]);
    }

    public function changeStatus(Request $request, Payment $payment)
    {
        $user = auth()->user();

        if ($payment->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'status' => ['bail', 'required', 'numeric', 'in:1,0'],
            'pin' => ['bail', 'required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        $payment->status = $request->status;
        $payment->save();

        return redirect()
            ->back()
            ->with('message', "Payment " . ($request->status == Payment::STATUS_CONFIRMED ? 'confirm' : 'reject') . "ed successfully!");
    }

    public function printOut(Payment $payment, $employee = null)
    {
        $user = $employee ? $employee->user : auth()->user();

        if ($payment->user_id != $user->id) {
            abort(404);
        }

        return view('payments.print', ['payment' => $payment, 'employee' => $employee]);
    }

    public function printOutEm(Payment $payment)
    {
        return $this->printOut($payment, Employee::getEmployee());
    }
}
