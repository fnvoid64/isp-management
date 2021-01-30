<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        return view('statistics.list', ['request' => $request]);
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();

            $data = [];

            if ($request->filled('employee')) {
                $employee = $user->employees()->findOrFail($request->employee);
            }

            if ($request->filled('date')) {
                $date_arr = explode(':', $request->date);
            }

            $date = new \DateTime();

            for ($i = 0; $i <= 30; $i++) {
                $payments = $user->payments()
                    ->whereDate('created_at', '=', $date->format('Y-m-d'))
                    ->sum('amount');
                $invoices = $user->invoices()
                    ->whereDate('created_at', '=', $date->format('Y-m-d'))
                    ->sum('amount');
                $invoices_due = $user->invoices()
                    ->whereDate('created_at', '=', $date->format('Y-m-d'))
                    ->sum('due');
                $customers = $user->customers()
                    ->whereDate('created_at', '=', $date->format('Y-m-d'))
                    ->count();
                $row = [
                    'date' => $date->format('d-m-Y'),
                    'customers' => $customers,
                    'revenue' => $payments,
                    'sale' => $invoices,
                    'due' => $invoices_due
                ];
                $data['data'][] = $row;
                $date->modify('-1 day');
            }

            $data['nextDate'] = $date->format('Y-m-d');

            if ($request->filled('nextDate')) {
                $date->modify($request->nextDate);
                $date->modify('+30 days');
                $data['prevDate'] = $date->format('Y-m-d');
            }

            if ($request->filled('date')) {
                if ($date_arr[1] >= $data['nextDate']) {
                    $data['nextDate'] = null;
                    $data['prevDate'] = $date_arr[0];
                }
            }

            return $data;
        }

        return false;
    }
}
