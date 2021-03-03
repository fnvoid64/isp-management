<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\User;
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
            $customers_wmobile = $customers->where('mobile', 'not like', "%1998811%");

            foreach ($customers->get() as $customer) {
                foreach ($customers->invoices()->where('status', '!=', Invoice::STATUS_PAID)->get() as $invoice) {
                    $invoice->status = Invoice::STATUS_CANCELLED;
                    $invoice->save();
                }
            }
        }
    }
}
