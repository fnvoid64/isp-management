<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $this_month = [
            date("Y-m-01"),
            date('Y-m-d')
        ];

        $date = new \DateTime();
        $date->modify("last day of previous month");

        $last_month = [
            $date->format("Y-m-01"),
            $date->format('Y-m-d')
        ];

        $user = auth()->user();
        $data = new \stdClass();
        $data->customer_count = $user->customers()->count();
        $data->total_revenue = round($user->payments()->sum('amount'), 2);
        $data->total_dues = round($user->invoices()->sum('due'), 2);
        $data->package_count = $user->packages()->count();
        $data->this_month_rev = round($user->payments()->whereBetween('created_at', $this_month)->sum('amount'), 2);
        $data->last_month_rev = round($user->payments()->whereBetween('created_at', $last_month)->sum('amount'), 2);

        $pending_customers = $user->customers()->where('customers.status', Customer::STATUS_PENDING)->limit(5)->get();
        $pending_payments = $user->payments()
            ->where('payments.status', Payment::STATUS_PENDING)
            ->limit(5)
            ->with(['customer:id,name', 'employee:id,name'])
            ->get();

        $date = new \DateTime('last day of this month');
        $data->chart_labels = [];
        $data->chart_data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data->chart_labels[] = $date->format('M, y');
            $data->chart_data[] = round($user->payments()->whereBetween('created_at', [$date->format('Y-m-01'), $date->format('Y-m-d')])->sum('amount'), 2);
            $date->modify('-1 month');
        }

        $data->chart_labels = array_reverse($data->chart_labels);
        $data->chart_data = array_reverse($data->chart_data);

        return view('dashboard', ['data' => $data, 'pending_customers' => $pending_customers, 'pending_payments' => $pending_payments]);
    }

    public function userProfile()
    {
        return view('profile.user_profile', ['user' => auth()->user()]);
    }

    public function userProfileStore(Request $request)
    {
        $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255'],
            'f_name' => ['bail', 'nullable', 'string', 'max:255'],
            'm_name' => ['bail', 'nullable', 'string', 'max:255'],
            'mobile' => ['bail', 'required', 'numeric', 'digits:11'],
            'address' => ['required'],
            'company_name' => ['bail', 'nullable', 'string', 'max:255'],
            'company_short' => ['bail', 'nullable', 'string', 'max:5'],
            'photo' => ['bail', 'nullable', 'image', 'max:2048']
        ]);
        $user = auth()->user();

        if ($request->mobile != $user->mobile) {
            if (User::where('mobile', $request->mobile)->exists()) {
                return redirect()->back()->withErrors('Mobile must be unique!');
            }
        }

        if ($request->has('photo')) {
            $user->photo = $request->file('photo')->store('user', 'public');
        }

        $user->name = $request->name;
        $user->f_name = $request->f_name;
        $user->m_name = $request->m_name;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->company_name = $request->company_name;
        $user->company_short = $request->company_short;
        $user->save();

        return redirect()
            ->back()
            ->with('message', 'Profile updated successfully!');
    }

    public function changePasswordForm()
    {
        return view('profile.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['bail', 'required', 'string'],
            'password' => ['bail', 'required', 'string', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors('Old password is wrong!');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->back()
            ->with('message', 'Password updated successfully!');
    }

    public function changePinForm()
    {
        return view('profile.change_pin');
    }

    public function changePin(Request $request)
    {
        $request->validate([
            'old_pin' => ['bail', 'required', 'string'],
            'pin' => ['bail', 'required', 'string', 'confirmed'],
        ]);

        $user = auth()->user();

        if ($request->old_pin != $user->pin) {
            return redirect()->back()->withErrors('Old PIN is wrong!');
        }

        $user->pin = $request->pin;
        $user->save();

        return redirect()
            ->back()
            ->with('message', 'PIN updated successfully!');
    }
}
