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
        $user = auth()->user();
        if ($user->sms < 1) {
            return redirect()->back()->withErrors(['errors' => 'You have no SMS, please buy some!']);
        }

        // api
        $api = "http://sms.publicia.net/sms/api?action=send-sms&api_key=SmdhS0lmaWNNcWQ9PUJCcUVpSWk=&to=880$customer->mobile&sms=" . urlencode($request->body);
        $data = json_decode(file_get_contents($api));

        if ($data->code != 'ok') {
            return redirect()->back()->withErrors(['errors' => 'SMS Gateway error! Please contact Pranto.']);
        }

        $user->sms -= 1;
        $user->save();

        return redirect()
            ->back()
            ->with('message', 'SMS Sent!');
    }
}
