<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyCharge extends Model {
    const PAID_VIA = [
        'ADMIN' => 'ADMIN' ,
        'BEHPARDAKHT' => 'BEHPARDAKHT' ,
    ];

    public function tenant (): BelongsTo {
        return $this->belongsTo(Tenant::class);
    }

    public function fiscalYear (): BelongsTo {
        return $this->belongsTo(FiscalYear::class);
    }

    public function scopeDueDatePassed ( Builder $query ): Builder {
        return $query->where('due_date' , '<' , now());
    }

    public function scopeDueDatePassedMoreThanOneMonth ( Builder $query ): Builder {
        return $query->where('due_date' , '<' , now()->subDays(31));
    }

    public function scopePaid ( Builder $query ): Builder {
        return $query->whereNotNull('paid_at');
    }

    public function scopeNotPaid ( Builder $query ): Builder {
        return $query->whereNull('paid_at');
    }

    public function getPersianMonthAttribute () {
        switch ( $this->month ) {
            case 1:
                return "فروردین";
            case 2:
                return "اردیبهشت";
            case 3:
                return "خرداد";
            case 4:
                return "تیر";
            case 5:
                return "مرداد";
            case 6:
                return "شهریور";
            case 7:
                return "مهر";
            case 8:
                return "آبان";
            case 9:
                return "آذر";
            case 10:
                return "دی";
            case 11:
                return "بهمن";
            case 12:
                return "اسفند";
            default:
                return "ماه نامعتبر"; // For invalid month numbers
        }
    }

    public function getFinancialMonthNameAttribute(){
        return verta($this->due_date)->subMonth()->subDay()->format('%B');
    }

    public function getFinalAmountAttribute () {
        if ( $this->tenant->tenant_type_id == 1 || $this->tenant->tenant_type_id == 2 ) {
            if ( $this->tenant->has_passed_due_date_hazine_omrani ) {
                return $this->original_amount;
            }
            if ( $this->tenant->other && $this->tenant->other_has_debt ) {
                return $this->original_amount;
            }
            if ( $this->tenant->other && $this->tenant->other_has_monthly_charge_due_date_passed_and_not_paid ) {
                return $this->original_amount;
            }
            if ( $this->tenant->debt_amount > Setting::getMinDebtAmount() ) {
                return $this->original_amount;
            }
            #
            $discount_percent = 10;
            // check if 10 days passed from $this->due_date
            if ( Carbon::parse($this->due_date)
                       ->isPast() && Carbon::parse($this->due_date)
                                           ->diffInDays(Carbon::now()) >= 5 ) {
                return $this->original_amount;
            }
            else {
                return ( ( 100 - $discount_percent ) / 100 ) * $this->original_amount;
            }
        }
        else {
            return $this->original_amount;
        }
    }

    public function getSubjectAndMonthAttribute () {
        return "پرداخت شارژ " . $this->financial_month_name;
    }
}
