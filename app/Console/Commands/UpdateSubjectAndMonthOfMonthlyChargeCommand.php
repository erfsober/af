<?php

namespace App\Console\Commands;

use App\Models\MonthlyCharge;
use App\Models\Transaction;
use Illuminate\Console\Command;

class UpdateSubjectAndMonthOfMonthlyChargeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-subject-and-month-of-monthly-charge-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::query()
            ->whereNotNull('monthly_charge_id')
            ->get();

        foreach ($transactions as $transaction){
            $transaction->subject = MonthlyCharge::find($transaction->monthly_charge_id)->financial_month_name;
            $transaction->save();
        }
    }
}
