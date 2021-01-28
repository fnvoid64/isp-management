<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return view('invoices.list', ['request' => $request]);
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();
            $invoices = $user
                ->invoices()
                ->select(['id', 'amount', 'due', 'customer_id', 'status', 'created_at']);

            if ($request->filled('customer')) {
                $customer = $user->customers()->findOrFail($request->customer);
                $invoices = $customer->invoices();
            }

            if ($request->filled('searchQuery') && $request->searchQuery != '#') {
                $request->searchQuery = ltrim($request->searchQuery, '#');
                $invoices->where('id', 'ilike', '%' . $request->searchQuery . '%');
            }

            if ($request->filled('status')) {
                $invoices = $invoices->where('status', $request->status);
            }

            $invoices = $invoices
                ->orderBy('id', 'DESC')
                ->with('customer:id,name')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            return $invoices;
        }

        return false;
    }

    public function show(Invoice $invoice)
    {
        $user = auth()->user();

        if ($invoice->user_id != $user->id) {
            abort(404);
        }

        return view('invoices.show', ['invoice' => $invoice]);
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $user = auth()->user();

        if ($invoice->user_id != $user->id) {
            abort(404);
        }

        if ($invoice->status == Invoice::STATUS_PAID || $invoice->status == Invoice::STATUS_CANCELLED) {
            return redirect()->back()->withErrors(['errors' => 'Invoice is already paid!']);
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'gt:1'],
            'type' => ['required', 'numeric', 'in:1,2,3']
        ]);

        if ($request->amount > $invoice->due) {
            return redirect()->back()->withErrors(['errors' => 'Payment amount is greater than invoice due!']);
        }

        $payment = $user->payments()->create([
            'customer_id' => $invoice->customer->id,
            'amount' => $request->amount,
            'type' => $request->type
        ]);

        if ($request->amount == $invoice->due) {
            $invoice->due = 0;
            $invoice->status = Invoice::STATUS_PAID;
        } else {
            $invoice->due = round($invoice->due - $request->amount, 2);
            $invoice->status = Invoice::STATUS_PARTIAL_PAID;
        }

        $invoice->payments()->attach($payment);
        $invoice->save();

        return redirect()
            ->back()
            ->with('message', "Payment for Invoice #$invoice->id was successful! print");
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        $user = auth()->user();

        if ($invoice->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'pin' => ['required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        if ($invoice->status == Invoice::STATUS_PAID || $invoice->status == Invoice::STATUS_CANCELLED) {
            return redirect()->back()->withErrors(['errors' => 'Invoice is already paid/cancelled!']);
        }

        $invoice->status = Invoice::STATUS_CANCELLED;
        if ($invoice->save()) {
            return redirect(route('invoices'))->with('message', 'Invoice Cancelled!');
        }

        return redirect()->back()->withErrors(['errors' => 'Delete ERROR!']);
    }
}
