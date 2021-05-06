<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        return view('customers.list', compact('request'));
    }

    public function indexEmployee(Request $request)
    {
        return view('customers.list_employee', compact('request'));
    }

    public function indexData(Request $request, $user = null)
    {
        if ($request->expectsJson()) {
            $user = $user ? $user : auth()->user();
            $customers = $user
                ->customers()
                ->select(['id', 'name', 'mobile', 'address', 'status']);

            if ($request->filled('package')) {
                $package = $user->packages()->findOrFail($request->package);
                $customers = $package->customers();
            }


            if ($request->filled('searchQuery') && $request->searchQuery != '0') {
                $request->searchQuery = ltrim($request->searchQuery, '0');
                $customers->where('name', 'ilike', '%' . $request->searchQuery . '%')
                    ->orWhere('mobile', 'ilike', '%' . $request->searchQuery . '%');
            }

            if ($request->filled('status')) {
                $customers = $customers->where('status', $request->status);
            }

            if ($request->filled('area')) {
                $customers = $customers->where('area_id', $request->area);
            }

            if ($request->filled('connectionPoint')) {
                $customers = $customers->where('connection_point_id', $request->connectionPoint);
            }

            if ($request->filled('p_type')) {
                //dd($request->p_type);
                $packages = $user->packages()->where('packages.type', intval($request->p_type))->get();

                $customers = null;

                foreach ($packages as $p) {
                    if ($customers) {
                        $customers = $p->customers()->orderBy('id', 'DESC')->get()->merge($customers);
                    } else {
                        $customers = $p->customers()->get();
                        //dd($customers);
                    }
                }

                //dd(count($customers));
            } else {
                $customers = $customers
                    ->orderBy('id', 'DESC')
                    ->paginate($request->per_page ?? 20, ['*'], 'page', $request->page ?? 1);
            }
            

            $customers->data = $customers->each(function ($c) {
                $c->dues = $c->invoices()->whereIn('status', [\App\Models\Invoice::STATUS_UNPAID, \App\Models\Invoice::STATUS_PARTIAL_PAID])->sum('due');
                return $c;
            });

            return $customers;
        }

        return false;
    }

    public function indexDataEmployee(Request $request)
    {
        $employee = Employee::getEmployee();
        return $this->indexData($request, $employee->user);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function createEmployee()
    {
        return view('customers.create_employee');
    }

    public function store(Request $request, $employee = null)
    {
        $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255'],
            'f_name' => ['bail', 'nullable', 'string', 'max:255'],
            'm_name' => ['bail', 'nullable', 'string', 'max:255'],
            'mobile' => ['bail', 'required', 'numeric', 'digits:11', 'unique:customers'],
            'nid' => ['bail', 'nullable', 'numeric', 'unique:customers'],
            'address' => ['required'],
            'area' => ['required', 'exists:areas,id'],
            'connection_point' => ['bail', 'nullable', 'exists:connection_points,id'],
            'package' => ['required', 'array'],
            'net_user' => ['nullable', 'string', 'max:255'],
            'net_pass' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'gt:1'],
            'comment' => ['nullable', 'string']
        ]);

        $user = $employee ? $employee->user : auth()->user();

        // Create Customer
        $customer = $user->customers()->create([
            'employee_id' => $employee ? $employee->id : null,
            'name' => $request->name,
            'f_name' => $request->f_name,
            'm_name' => $request->m_name,
            'mobile' => $request->mobile,
            'nid' => $request->nid,
            'address' => $request->address,
            'area_id' => $request->area,
            'connection_point_id' => $request->connection_point ?? null,
            'net_user' => $request->net_user,
            'net_pass' => $request->net_pass
        ]);

        $customer->packages()->attach($request->package);

        // Generate Invoice
        //if (!$request->filled('no_invoice')) {
            //$this->generateInvoice($user, $customer, $request->package);
        //}

        if ($request->filled('amount') && !$employee) {
            $invoice = $user->invoices()->create([
                'customer_id' => $customer->id,
                'amount' => $request->amount,
                'due' => $request->amount,
                'comment' => $request->comment
            ]);
        }

        return redirect()
            ->back()
            ->with('message', "Customer $customer->name successfully created!");
    }

    public function storeEmployee(Request $request)
    {
        return $this->store($request, Employee::getEmployee());
    }

    protected function generateInvoice($user, Customer $customer): void
    {
        $billableDays = date("t") - date("j");
        $billableAmount = 0;
        $packages = $customer->packages()->get();

        foreach ($packages as $package) {
            //$package = $user->packages()->findOrFail($package->id);
            $billableAmount += round($billableDays * ($package->sale_price / 30), 2);
        }

        if ($billableAmount > 0) {
            $invoice = $user->invoices()->create([
                'customer_id' => $customer->id,
                'amount' => $billableAmount,
                'due' => $billableAmount,
                'package_ids' => implode(',', $packages->pluck('id')->toArray())
            ]);
        }
    }

    public function show(Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        return view('customers.show', compact('customer'));
    }

    public function showEmployee(Customer $customer)
    {
        $employee = Employee::getEmployee();
        $user = $employee->user;

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        return view('customers.show_employee', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        return view('customers.edit', ['customer' => $customer]);
    }


    public function update(Request $request, Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255'],
            'f_name' => ['bail', 'nullable', 'string', 'max:255'],
            'm_name' => ['bail', 'nullable', 'string', 'max:255'],
            'mobile' => ['bail', 'required', 'numeric', 'digits:11'],
            'nid' => ['bail', 'nullable', 'numeric'],
            'address' => ['required'],
            'area' => ['required', 'exists:areas,id'],
            'connection_point' => ['bail', 'nullable', 'exists:connection_points,id'],
            'package' => ['required', 'array'],
            'net_user' => ['nullable', 'string', 'max:255'],
            'net_pass' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->mobile != $customer->mobile) {
            if (Customer::where('mobile', $request->mobile)->exists()) {
                return redirect()->back()->withErrors('Mobile must be unique!');
            }
        }

        if ($request->filled('nid') && $request->nid != $customer->nid) {
            if (Customer::where('nid', $request->nid)->exists()) {
                return redirect()->back()->withErrors('NID must be unique!');
            }
        }

        $packages = $customer->packages()->select('packages.id')->pluck('id')->toArray();

        $customer->name = $request->name;
        $customer->f_name = $request->f_name;
        $customer->m_name = $request->m_name;
        $customer->mobile = $request->mobile;
        $customer->nid = $request->nid;
        $customer->address = $request->address;
        $customer->area_id = $request->area;
        $customer->connection_point_id = $request->connection_point ?? null;
        $customer->net_user = $request->net_user;
        $customer->net_pass = $request->net_pass;

        if ($request->package != $packages) {
            $customer->status = Customer::STATUS_PENDING;
            $customer->packages()->detach();
            $customer->packages()->attach($request->package);
        }

        $customer->save();

        return redirect()
            ->back()
            ->with('message', "Customer $customer->name successfully updated!");
    }

    public function changeStatus(Request $request, Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        if ($request->expectsJson()) {
            $request->validate([
                'status' => ['required', 'numeric', 'in:1,2,0']
            ]);

            $customer->status = $request->status;
            $customer->save();

            if ($customer->status == Customer::STATUS_ACTIVE && $request->status == Customer::STATUS_ACTIVE) {
                $this->generateInvoice($user, $customer);
            }

            return $customer->status;
        }

        return false;
    }

    public function makePayment(Request $request, Customer $customer, $employee = null)
    {
        $user = $employee ? $employee->user : auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'gt:1'],
            'type' => ['required', 'numeric', 'in:1,2,3']
        ]);

        $invoices = $customer->invoices()->whereIn('status', [\App\Models\Invoice::STATUS_UNPAID, \App\Models\Invoice::STATUS_PARTIAL_PAID]);
        $dueAmount = $invoices->sum('due');

        if ($dueAmount > 0) {
            if ($request->amount <= $dueAmount) {
                $paidAmount = $request->amount;

                // Make Payment
                $payment = $user->payments()->create([
                    'employee_id' => $employee ? $employee->id : null,
                    'customer_id' => $customer->id,
                    'amount' => $request->amount,
                    'type' => $request->type,
                    'status' => $employee ? Payment::STATUS_PENDING : Payment::STATUS_CONFIRMED
                ]);

                $months = [];

                // Mark Invoice
                foreach ($invoices->get() as $invoice) {
                    if ($paidAmount > 0) {
                        if ($paidAmount >= $invoice->due) {
                            $invoice->status = Invoice::STATUS_PAID;
                            $paidAmount = $paidAmount - $invoice->due;
                            $invoice->due = 0;
                        } else {
                            $invoice->due = round($invoice->due - $paidAmount, 2);
                            $invoice->status = Invoice::STATUS_PARTIAL_PAID;
                            $paidAmount = 0;
                        }

                        $invoice->payments()->attach($payment);
                        $invoice->save();
                        $months[] = $invoice->created_at->format('M');
                    }
                }

                $months = implode(", ", $months);
                $message = "-- Bill Paid --\nCustomer ID: #{$customer->id}\nAmount: Tk. {$request->amount}\nBill Months: {$months}\nThanks, Somor Kapali Biswas.";
                $api = "http://sms.publicia.net/sms/api?action=send-sms&api_key=SmdhS0lmaWNNcWQ9PUJCcUVpSWk=&to=880$customer->mobile&sms=" . urlencode($message);
                $data = json_decode(file_get_contents($api));

                return redirect()
                    ->back()
                    ->with('message', 'Payment completed!'  . '<a href="' . route($employee ? 'employee_payments.print' : 'payments.print', ['payment' => $payment->id]) .'" class="btn btn-primary btn-sm">Print</a>');
            } else {
                return redirect()->back()->withErrors('Amount is greater than due!');
            }
        } else {
            return redirect()->back()->withErrors('There is no due!');
        }
    }

    public function makeInvoice(Request $request, Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'amount' => ['bail', 'required', 'numeric', 'gt:1'],
            'comment' => ['nullable']
        ]);

        $invoice = $user->invoices()->create([
            'customer_id' => $customer->id,
            'amount' => $request->amount,
            'due' => $request->amount,
            'comment' => $request->comment
        ]);

        return redirect()
            ->back()
            ->with('message', "Invoice of amount BDT $invoice->amount created successfully!");
    }

    public function makePaymentEmployee(Request $request, Customer $customer)
    {
        return $this->makePayment($request, $customer, Employee::getEmployee());
    }

    public function destroy(Request $request, Customer $customer)
    {
        $user = auth()->user();

        if ($customer->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'pin' => ['required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        if ($customer->delete()) {
            return redirect(route('customers'))->with('message', 'Customer successfully deleted!');
        }

        return redirect()->back()->withErrors(['errors' => 'Delete ERROR!']);
    }
}
