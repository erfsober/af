<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model {
    const PAID_VIA = [
        'ADMIN' => 'ADMIN' ,
        'BEHPARDAKHT' => 'BEHPARDAKHT' ,
        'PASARGAD' => 'PASARGAD' ,
    ];

    public function tenant (): BelongsTo {
        return $this->belongsTo(Tenant::class);
    }

    public function other (): BelongsTo {
        return $this->belongsTo(Other::class);
    }

    public function monthlyCharge (): BelongsTo {
        return $this->belongsTo(MonthlyCharge::class);
    }

    public function hazineOmrani (): BelongsTo {
        return $this->belongsTo(HazineOmrani::class, 'hazine_omrani_id');
    }

    public function otherMonthlyCharge (): BelongsTo {
        return $this->belongsTo(OtherMonthlyCharge::class);
    }

    public function scopePaid ( Builder $query ): Builder {
        return $query->whereNotNull('paid_at');
    }

    public function scopeNotPaid ( Builder $query ): Builder {
        return $query->whereNull('paid_at');
    }

    public function getStatusAttribute () {
        if ( $this->paid_at ) {
            return "پرداخت موفق";
        }
        if ( $this->failed_at ) {
            return "پرداخت ناموفق";
        }
        else {
            return "در حال پرداخت";
        }
    }

    public function hasPenalty () {
        return $this->amount > $this->original_amount;
    }

    public function hasDiscount () {
        return $this->amount < $this->original_amount;
    }

    public function penaltyAmount () {
        if ( $this->hasPenalty() ) {
            return $this->amount - $this->original_amount;
        }

        return 0;
    }

    public function discountAmount () {
        if ( $this->hasDiscount() ) {
            return $this->original_amount - $this->amount;
        }

        return 0;
    }

    public function zoodtarAmount () {
        if ($monthly_charge = $this->monthlyCharge){
            if(Carbon::parse($monthly_charge->due_date)->greaterThan(Carbon::parse($this->created_at))){
                return $this->amount;
            }
        }elseif ($hazine_omrani = $this->hazineOmrani){
            if(Carbon::parse($hazine_omrani->ended_at)->greaterThan(Carbon::parse($this->created_at))){
                return $this->amount;
            }
        }
        return 0;
    }

    public function penaltyPercent () {
        if ( $this->hasPenalty() ) {
            return ( $this->penaltyAmount() / $this->original_amount ) * 100;
        }

        return 0;
    }

    public function discountPercent () {
        if ( $this->hasDiscount() ) {
            return ( $this->discountAmount() / $this->original_amount ) * 100;
        }

        return 0;
    }

    public function verifyLogs (): HasMany {
        return $this->hasMany(VerifyLog::class , 'transaction_id');
    }

    public function getTenantOrOtherAttribute () {
        return $this->tenant_id ? $this->tenant->tenantType->type_fa : 'واحد متفرقه';
    }

    public function paidSoonerOrLater()
    {
        if ($this->monthly_charge_id && $this->paid_at){
            $monthlyCharge = $this->monthlyCharge;
            if($monthlyCharge){
                $dueDate = Carbon::parse($monthlyCharge->due_date);
                $paidDate = Carbon::parse($this->paid_at);

                return $paidDate->diffInDays($dueDate);
            }
        }

        return null;
    }
}
