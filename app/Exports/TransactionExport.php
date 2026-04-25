<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class TransactionExport implements FromView , WithEvents {
    public $started_at;
    public $ended_at;
    public $transaction_type;
    public $paid_via;

    public function __construct ( $started_at , $ended_at , $transaction_type , $paid_via ) {
        $this->started_at = $started_at;
        $this->ended_at = $ended_at;
        $this->transaction_type = $transaction_type;
        $this->paid_via = $paid_via;
    }

    public function view (): View {
        $transaction_type = $this->transaction_type;

        return view('exports.transactions' , [
            'transactions' => Transaction::query()
                                         ->paid()
                                         ->whereBetween('paid_at' , [
                                             $this->started_at ,
                                             $this->ended_at ,
                                         ])
                                         ->when($transaction_type == 'tejari' , function ( Builder $query ) use ( $transaction_type ) {
                                             $query->whereHas('tenant' , function ( $q ) use ( $transaction_type ) {
                                                 $q->where('tenant_type_id' , 1);
                                             });
                                         })
                                         ->when($transaction_type == 'edari' , function ( Builder $query ) use ( $transaction_type ) {
                                             $query->whereHas('tenant' , function ( $q ) use ( $transaction_type ) {
                                                 $q->where('tenant_type_id' , 2);
                                             });
                                         })
                                         ->when($transaction_type == 'malekiati' , function ( Builder $query ) use ( $transaction_type ) {
                                             $query->whereNotNull('ownership_debt_id');
                                         })
                                         ->when($transaction_type == 'motefareghe' , function ( Builder $query ) use ( $transaction_type ) {
                                             $query->whereNotNull('other_id');
                                         })
                                         ->when($this->paid_via , function ( Builder $query ) {
                                             $query->where('paid_via' , $this->paid_via);
                                         })
                                         ->orderByDesc('id')
                                         ->get() ,
        ]);
    }

    public function registerEvents (): array {
        return [
            AfterSheet::class => function ( AfterSheet $event ) {
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            } ,
        ];
    }
}
