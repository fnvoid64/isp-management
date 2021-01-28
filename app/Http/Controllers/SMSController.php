<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function create(Request $request)
    {
        return view('sms.create', ['request' => $request]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer' => ['required', 'numeric'],
            'body' => ['required', 'string', 'max:160']
        ]);

        $customer = auth()->user()->customers()->findOrFail($request->customer);

        // todo send sms

        return redirect()
            ->back()
            ->with('message', 'SMS Sent!');
    }
}
