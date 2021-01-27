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
                ->select(['id', 'amount', 'due', 'customer_id', 'status']);

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
                ->latest()
                ->with('customer:id,name')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            return $invoices;
        }

        return false;
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', ['invoice' => $invoice]);
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
