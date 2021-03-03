<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoice for all customers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Generate invoice for Somor only
        $user = User::where(['email' => 'somor@softmight.com'])->first();

        if ($user) {
            $customers = $user->customers();
            //$customers_wmobile = $customers->where('mobile', 'not like', "%1998811%");

            foreach ($customers->get() as $customer) {
                $customer->status = Customer::STATUS_ACTIVE;

                $amount = 0;
                $packages = [];

                foreach ($customer->packages()->get() as $package) {
                    $packages[] = $package->id;
                    $amount += $package->sale_price;
                }

                $jan_invoice = $customer->invoices()->create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'due' => $amount,
                    'package_ids' => implode(",", $packages),
                    'created_at' => new Carbon('30-01-2021')
                ]);

                $feb_invoice = $customer->invoices()->create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'due' => $amount,
                    'package_ids' => implode(",", $packages),
                    'created_at' => new Carbon('last day of last month')
                ]);
            }
        }
    }
}
