<?php

namespace App\Http\Controllers\Tenant;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\BedehiOmrani;
use App\Models\Debt;
use App\Models\HazineOmrani;
use App\Models\MonthlyCharge;
use App\Models\OtherDebt;
use App\Models\OtherMonthlyCharge;
use App\Models\OwnershipDebt;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\VerifyLog;
use App\Services\Dorsa;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TransactionController extends Controller {
    public function index ( Request $request ) {
        $records = Transaction::query()
                              ->where('tenant_id' , Auth::guard('tenant')
                                                        ->id())
                              ->when($request->get('search') , function ( Builder $query ) use ( $request ) {
                                  $search = $request->get('search');
                                  $query->where('tx_id' , 'like' , '%' . $search . '%');
                              })
                              ->orderByDesc('id')
                              ->get();

        return view('metronic.tenant.transactions.index' , compact('records'));
    }

    public function pdf ( $id ) {
        $transaction = Transaction::query()
                                  ->where('tenant_id' , Auth::guard('tenant')
                                                            ->id())
                                  ->paid()
                                  ->findOrFail($id);
        $pdf = PDF::loadView('pdf.transaction' , compact('transaction') , [] , [ 'format' => 'A5-L' ]);

        return $pdf->stream('t-' . $transaction->id . '.pdf');
    }

    public function downloadPdf ( $enc ) {
        $transaction = Transaction::query()
                                  ->paid()
                                  ->where('tx_id' , $enc)
                                  ->firstOrFail();
        $pdf = PDF::loadView('pdf.transaction' , compact('transaction') , [] , [ 'format' => 'A5-L' ]);

        return $pdf->download(Str::random() . '.pdf');
    }

    public function generateUrl ( Request $request ) {

        $transaction = null;
        $phoneNumber = \auth()->guard('tenant')->user()->phone_number;
        if ( $monthly_charge_id = $request->get('monthly_charge_id') ) {
            $monthly_charge = MonthlyCharge::query()
                                           ->where('id' , $monthly_charge_id)
                                           ->whereNull('paid_at')
                                           ->firstOrFail();
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $monthly_charge->tenant->full_name ,
                                                   'tenant_id' => $monthly_charge->tenant_id ,
                                                   'monthly_charge_id' => $monthly_charge->id ,
                                                   'original_amount' => $monthly_charge->original_amount ,
                                                   'amount' => $monthly_charge->final_amount ,
                                                   'subject' => $monthly_charge->subject_and_month ,
                                               ]);
        }
        else if ( $hazine_omrani_id = $request->get('hazine_omrani_id') ) {
            $hazine_omrani = HazineOmrani::query()
                                         ->where('id' , $hazine_omrani_id)
                                         ->whereNull('paid_at')
                                         ->firstOrFail();
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $hazine_omrani->tenant->full_name ,
                                                   'tenant_id' => $hazine_omrani->tenant_id ,
                                                   'hazine_omrani_id' => $hazine_omrani->id ,
                                                   'original_amount' => $hazine_omrani->original_amount ,
                                                   'amount' => $hazine_omrani->final_amount ,
                                                   'subject' => $hazine_omrani->subject_and_month ,
                                               ]);
        }
        else if ( $debt_id = $request->get('debt_id') ) {
            $debt = Debt::query()
                        ->where('id' , $debt_id)
                        ->whereNull('paid_at')
                        ->firstOrFail();
            $request->validate([
                                   'debt_amount' => [
                                       'required' ,
                                   ] ,
                               ] , [
                                   'debt_amount.required' => 'مبلغ الزامی است' ,
                               ]);
            $removed_comma = str_replace(',' , '' , $request->get('debt_amount'));
            $debt_amount_to_pay = Tenant::englishNumber($removed_comma);
            if ( $debt_amount_to_pay > $debt->amount ) {
                flash()
                    ->options([
                                  'timeout' => 3000 ,
                                  'position' => 'top-left' ,
                              ])
                    ->addError('مبلغ بیش از حد مجاز' , 'خطا');

                return redirect()->back();
            }
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $debt->tenant->full_name ,
                                                   'tenant_id' => $debt->tenant_id ,
                                                   'debt_id' => $debt_id ,
                                                   'original_amount' => $debt_amount_to_pay ,
                                                   'amount' => $debt_amount_to_pay ,
                                                   'subject' => 'پرداخت بدهی' ,
                                               ]);
        }
        else if ( $ownership_debt_id = $request->get('ownership_debt_id') ) {
            $ownership_debt = OwnershipDebt::query()
                                           ->where('id' , $ownership_debt_id)
                                           ->whereNull('paid_at')
                                           ->firstOrFail();
            $request->validate([
                                   'ownership_debt_amount' => [
                                       'required' ,
                                   ] ,
                               ] , [
                                   'ownership_debt_amount.required' => 'مبلغ الزامی است' ,
                               ]);
            $removed_comma = str_replace(',' , '' , $request->get('ownership_debt_amount'));
            $ownership_debt_amount_to_pay = Tenant::englishNumber($removed_comma);
            if ( $ownership_debt_amount_to_pay > $ownership_debt->amount ) {
                flash()
                    ->options([
                                  'timeout' => 3000 ,
                                  'position' => 'top-left' ,
                              ])
                    ->addError('مبلغ بیش از حد مجاز' , 'خطا');

                return redirect()->back();
            }
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $ownership_debt->tenant->full_name ,
                                                   'tenant_id' => $ownership_debt->tenant_id ,
                                                   'ownership_debt_id' => $ownership_debt->id ,
                                                   'original_amount' => $ownership_debt_amount_to_pay ,
                                                   'amount' => $ownership_debt_amount_to_pay ,
                                                   'subject' => 'پرداخت هزینه مالکیتی' ,
                                               ]);
        }
        else if ( $bedehi_omrani_id = $request->get('bedehi_omrani_id') ) {
            $bedehi_omrani = BedehiOmrani::query()
                                         ->where('id' , $bedehi_omrani_id)
                                         ->whereNull('paid_at')
                                         ->firstOrFail();
            $request->validate([
                                   'bedehi_omrani_amount' => [
                                       'required' ,
                                   ] ,
                               ] , [
                                   'bedehi_omrani_amount.required' => 'مبلغ الزامی است' ,
                               ]);
            $removed_comma = str_replace(',' , '' , $request->get('bedehi_omrani_amount'));
            $bedehi_omrani_amount_to_pay = Tenant::englishNumber($removed_comma);
            if ( $bedehi_omrani_amount_to_pay > $bedehi_omrani->amount ) {
                flash()
                    ->options([
                                  'timeout' => 3000 ,
                                  'position' => 'top-left' ,
                              ])
                    ->addError('مبلغ بیش از حد مجاز' , 'خطا');

                return redirect()->back();
            }
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $bedehi_omrani->tenant->full_name ,
                                                   'tenant_id' => $bedehi_omrani->tenant_id ,
                                                   'bedehi_omrani_id' => $bedehi_omrani->id ,
                                                   'original_amount' => $bedehi_omrani_amount_to_pay ,
                                                   'amount' => $bedehi_omrani_amount_to_pay ,
                                                   'subject' => 'پرداخت بدهی عمرانی' ,
                                               ]);
        }
        else {
            dd("ERROR");
        }
        if ( $request->get('gateway') == 'pasargad' ) {
            $dorsa = new Dorsa();
            $result = $dorsa->makePurchaseTransaction($transaction->amount , $transaction->id, $phoneNumber);
            $transaction->tx_id = $result[ 'urlId' ];
            $transaction->paid_via = Transaction::PAID_VIA[ 'PASARGAD' ];
            $transaction->save();

            return redirect($result[ 'url' ]);
        }
        else {
            $invoice = ( new Invoice )->amount($transaction->amount / 10);

            return Payment::callbackUrl(route('web.verify'))
                          ->purchase($invoice , function ( $driver , $transactionId ) use ( $transaction ) {
                              $transaction->tx_id = $transactionId;
                              $transaction->save();
                          })
                          ->pay()
                          ->render();
        }
    }

    public function verify ( Request $request ) {
        $ok = false;
        $global_exception = null;
        /* PASARGAD */
        if ( $invoice_id = $request->get('invoiceId') ) {
            $tx_id = $invoice_id;
            $transaction = Transaction::query()
                                      ->find($invoice_id);
            $verify_log = VerifyLog::query()
                                   ->create([
                                                'transaction_id' => $transaction->id ,
                                                'request' => json_encode($request->all()) ,
                                            ]);
            try {
                $confirm = ( new Dorsa() )->confirmTransaction($invoice_id , $transaction->tx_id);
                $ok = true;
            }
            catch ( Exception  $exception) {
                $global_exception = $exception;
            }
        }
        /* END PASARGAD */
        else {
            $tx_id = $request->get('RefId') ?? $request->get('Authority') ?? $request->get('trackId');
            $transaction = Transaction::query()
                                      ->where('tx_id' , $tx_id)
                                      ->firstOrFail();
            $verify_log = VerifyLog::query()
                                   ->create([
                                                'transaction_id' => $transaction->id ,
                                                'request' => json_encode($request->all()) ,
                                            ]);
            try {
                $receipt = Payment::amount($transaction->amount / 10)
                                  ->transactionId($tx_id)
                                  ->verify();
                $ok = true;
            }
            catch ( Exception $exception ) {
                $global_exception = $exception;
            }
        }
        if ( $ok ) {

            $transaction->paid_at = now();
            $transaction->ref_id = $request->get('RefId') ?? $request->get('referenceNumber');
            $transaction->save();
            if ( $transaction->monthly_charge_id ) {
                $monthly_charge = MonthlyCharge::query()
                                               ->find($transaction->monthly_charge_id);
                $monthly_charge->paid_at = now();
                $monthly_charge->paid_amount = $transaction->amount;
                $monthly_charge->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            if ( $transaction->hazine_omrani_id ) {
                $hazine_omrani = HazineOmrani::query()
                                             ->find($transaction->hazine_omrani_id);
                $hazine_omrani->paid_at = now();
                $hazine_omrani->paid_amount = $transaction->amount;
                $hazine_omrani->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            if ( $transaction->debt_id ) {
                $debt = Debt::query()
                            ->find($transaction->debt_id);
                if ( $transaction->amount < $debt->amount ) {
                    $debt->amount = $debt->amount - $transaction->amount;
                }
                else {
                    $debt->paid_at = now();
                }
                $debt->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            if ( $transaction->ownership_debt_id ) {
                $ownership_debt_id = OwnershipDebt::query()
                                                  ->find($transaction->ownership_debt_id);
                if ( $transaction->amount < $ownership_debt_id->amount ) {
                    $ownership_debt_id->amount = $ownership_debt_id->amount - $transaction->amount;
                }
                else {
                    $ownership_debt_id->paid_at = now();
                }
                $ownership_debt_id->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            if ( $transaction->bedehi_omrani_id ) {
                $bedehi_omrani = BedehiOmrani::query()
                                             ->find($transaction->bedehi_omrani_id);
                if ( $transaction->amount < $bedehi_omrani->amount ) {
                    $bedehi_omrani->amount = $bedehi_omrani->amount - $transaction->amount;
                }
                else {
                    $bedehi_omrani->paid_at = now();
                }
                $bedehi_omrani->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            #
            if ( $transaction->other_monthly_charge_id ) {
                $other_monthly_charge = OtherMonthlyCharge::query()
                                                          ->find($transaction->other_monthly_charge_id);
                $other_monthly_charge->paid_at = now();
                $other_monthly_charge->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
            if ( $transaction->other_debt_id ) {
                $other_debt = OtherDebt::query()
                                       ->find($transaction->other_debt_id);
                if ( $transaction->amount < $other_debt->amount ) {
                    $other_debt->amount = $other_debt->amount - $transaction->amount;
                }
                else {
                    $other_debt->paid_at = now();
                }
                $other_debt->save();

                return view('payment.redirect' , [
                    'success' => true ,
                    'code' => $tx_id ,
                ]);
            }
        }
        else {
            $transaction->failed_at = now();
            $transaction->save();
            $verify_log->exception_message = $global_exception->getMessage();
            $verify_log->exception_code = $global_exception->getCode();
            $verify_log->save();

            return view('payment.redirect' , [
                'failed' => true ,
                'failed_message' => $global_exception->getMessage() ,
                'failed_code' => $global_exception->getCode() ,
            ]);
        }
    }

    public function export ( Request $request ) {
        $request->validate([
                               'started_at' => [ 'required' ] ,
                               'ended_at' => [ 'required' ] ,
                           ]);
        $started_at = Carbon::createFromTimestamp($request->get('started_at'))
                            ->startOfDay();
        $ended_at = Carbon::createFromTimestamp($request->get('ended_at'))
                          ->endOfDay();

        return Excel::download(new TransactionExport($started_at , $ended_at , $request->get('tenant_type_id') , null) , 'transactions.xlsx');
    }

    public function chooseGateway ( Request $request ) {
        $request->validate([
                               'generate_url' => [ 'required' ] ,
                           ]);
        $generate_url = $request->get('generate_url');

        return view('metronic.tenant.choose-gateway.index' , compact('generate_url'));
    }
}
