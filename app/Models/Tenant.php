<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tenant extends Authenticatable implements HasMedia {
    use Notifiable , InteractsWithMedia;

    protected $guard   = 'tenant';
    protected $guarded = [];
    protected $hidden  = [
        'password' ,
        'remember_token' ,
    ];

    public function registerMediaCollections (): void {
        $this->addMediaCollection('image')
             ->singleFile();
    }

    protected function serializeDate ( DateTimeInterface $date ) {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFullNameAttribute () {
        return $this->owner_first_name . " " . $this->owner_last_name . " " . $this->plaque;
    }

    public function tenantType (): BelongsTo {
        return $this->belongsTo(TenantType::class);
    }

    public function floor (): BelongsTo {
        return $this->belongsTo(Floor::class);
    }

    public function messages (): HasMany {
        return $this->hasMany(Message::class , 'tenant_id');
    }

    public function warnings (): HasMany {
        return $this->hasMany(Warning::class , 'tenant_id');
    }

    public function transactions (): HasMany {
        return $this->hasMany(Transaction::class , 'tenant_id');
    }

    public function monthlyCharges (): HasMany {
        return $this->hasMany(MonthlyCharge::class , 'tenant_id');
    }

    public function debts (): HasMany {
        return $this->hasMany(Debt::class , 'tenant_id');
    }

    public function ownershipDebts (): HasMany {
        return $this->hasMany(OwnershipDebt::class , 'tenant_id');
    }

    /** Methods **/
    public function addDebt ( $amount , $reason , $type ) {
        Debt::query()
            ->create([
                         'tenant_id' => $this->id ,
                         'amount' => $amount ,
                         'reason' => $reason ,
                         'type' => $type ,
                     ]);
    }

    public function removeDebt ( $debt_id ) {
        $debt = Debt::query()
                    ->find($debt_id);
        if ( !$debt ) {
            throw new Exception('بدهی یافت نشد');
        }
        if ( $debt->tenant_id != $this->id ) {
            throw new Exception('بدهی مربوط به این کاربر نیست');
        }
        if ( $debt->paid_at ) {
            throw new Exception('بدهی پرداخت شده قابل حذف نیست');
        }
        $debt->delete();
    }

    public function submitBestankari ( $amount ) {
        $first_unpaid_monthly_charge = $this->getFirstUnpaidMonthlyCharge();
        if ( !$first_unpaid_monthly_charge ) {
            return false;
        }
        if ( $amount > $first_unpaid_monthly_charge->original_amount ) {
            return false;
        }
        elseif ( $amount == $first_unpaid_monthly_charge->original_amount ) {
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $this->full_name ,
                                                   'tenant_id' => $this->id ,
                                                   'monthly_charge_id' => $first_unpaid_monthly_charge->id ,
                                                   'original_amount' => $first_unpaid_monthly_charge->original_amount ,
                                                   'amount' => $first_unpaid_monthly_charge->original_amount ,
                                                   'subject' => $first_unpaid_monthly_charge->subject_and_month ,
                                                   'paid_at' => now() ,
                                                   'ref_id' => 'AD' . rand() ,
                                                   'tx_id' => 'AD' . rand() ,
                                                   'paid_via' => Transaction::PAID_VIA[ 'ADMIN' ] ,
                                               ]);
            $first_unpaid_monthly_charge->paid_via = MonthlyCharge::PAID_VIA[ 'ADMIN' ];
            $first_unpaid_monthly_charge->paid_at = now();
            $first_unpaid_monthly_charge->paid_amount = $transaction->amount;
            $first_unpaid_monthly_charge->save();

            return true;
        }
        else {
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $this->full_name ,
                                                   'tenant_id' => $this->id ,
                                                   'monthly_charge_id' => $first_unpaid_monthly_charge->id ,
                                                   'original_amount' => $amount ,
                                                   'amount' => $amount ,
                                                   'subject' => $first_unpaid_monthly_charge->subject_and_month ,
                                                   'paid_at' => now() ,
                                                   'ref_id' => 'AD' . rand() ,
                                                   'tx_id' => 'AD' . rand() ,
                                                   'paid_via' => Transaction::PAID_VIA[ 'ADMIN' ] ,
                                               ]);
            $first_unpaid_monthly_charge->paid_via = MonthlyCharge::PAID_VIA[ 'ADMIN' ];
            $first_unpaid_monthly_charge->original_amount = $first_unpaid_monthly_charge->original_amount - $amount;
            $first_unpaid_monthly_charge->save();

            return true;
        }
    }
    public function submitBestankariForHazineOmrani ( $amount ) {
        $first = $this->getFirstUnpaidHazineOmrani();
        if ( !$first ) {
            return false;
        }
        if ( $amount > $first->original_amount ) {
            return false;
        }
        elseif ( $amount == $first->original_amount ) {
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $this->full_name ,
                                                   'tenant_id' => $this->id ,
                                                   'hazine_omrani_id' => $first->id ,
                                                   'original_amount' => $first->original_amount ,
                                                   'amount' => $first->original_amount ,
                                                   'subject' => $first->subject_and_month ,
                                                   'paid_at' => now() ,
                                                   'ref_id' => 'AD' . rand() ,
                                                   'tx_id' => 'AD' . rand() ,
                                                   'paid_via' => Transaction::PAID_VIA[ 'ADMIN' ] ,
                                               ]);
            $first->paid_via = MonthlyCharge::PAID_VIA[ 'ADMIN' ];
            $first->paid_at = now();
            $first->paid_amount = $transaction->amount;
            $first->save();

            return true;
        }
        else {
            $transaction = Transaction::query()
                                      ->create([
                                                   'tenant_name' => $this->full_name ,
                                                   'tenant_id' => $this->id ,
                                                   'hazine_omrani_id' => $first->id ,
                                                   'original_amount' => $amount ,
                                                   'amount' => $amount ,
                                                   'subject' => $first->subject_and_month ,
                                                   'paid_at' => now() ,
                                                   'ref_id' => 'AD' . rand() ,
                                                   'tx_id' => 'AD' . rand() ,
                                                   'paid_via' => Transaction::PAID_VIA[ 'ADMIN' ] ,
                                               ]);
            $first->paid_via = MonthlyCharge::PAID_VIA[ 'ADMIN' ];
            $first->original_amount = $first->original_amount - $amount;
            $first->save();

            return true;
        }
    }

    public function scopeWithUnpaidChargesCount ( $query ) {
        return $query->withCount([
                                     'monthlyCharges as unpaid_charges_count' => function ( $q ) {
                                         $q->notPaid()
                                           ->dueDatePassed();
                                     } ,
                                 ]);
    }

    public function generateMonthlyCharge ( FiscalYear $fiscal_year ) {
        for ( $i = 1 ; $i <= 11 ; $i++ ) {
            $due_date = verta(Carbon::parse($fiscal_year->started_at))
                ->addMonths($i - 1)
                ->startMonth()
                ->toCarbon();
            if ($i == 1) {
                $due_date->addDays(5);
            }
            MonthlyCharge::query()
                         ->firstOrCreate([
                                             'fiscal_year_id' => $fiscal_year->id ,
                                             'tenant_id' => $this->id ,
                                             'month' => $i ,
                                         ] , [
                                             'original_amount' => $this->monthly_charge_amount ,
                                             'due_date' => $due_date ,
                                         ]);
        }
    }

    public function getDefaultPasswordAttribute () {
        return $this->plaque . "@1403";
    }

    public function getPassedDueDateAmountAttribute () {
        $monthly_charges = $this->monthlyCharges()
                                ->notPaid()
                                ->dueDatePassed()
                                ->get();
        $total = 0;
        foreach ( $monthly_charges as $monthly_charge ) {
            $total += $monthly_charge->original_amount;
        }

        return $total;
    }

    public function getFirstUnpaidMonthlyCharge () {
        return $first_unpaid_monthly_charge = MonthlyCharge::query()
                                                           ->where('tenant_id' , $this->id)
                                                           ->notPaid()
                                                           ->first();
    }

    public function getFirstUnpaidHazineOmrani () {
        return HazineOmrani::query()
                                                           ->where('tenant_id' , $this->id)
                                                           ->notPaid()
                                                           ->first();
    }

    public function oneMonthPassedFromFirstUnpaidMonthlyCharge () {
        $first_unpaid_monthly_Charge = $this->getFirstUnpaidMonthlyCharge();
        if ( Carbon::parse($first_unpaid_monthly_Charge->due_date)
                   ->diffInDays(now()) > 31 ) {
            return true;
        }

        return false;
    }

    public function oneMonthPassedFromFirstUnpaidHazineOmrani () {
        $first_unpaid_monthly_Charge = $this->getFirstUnpaidHazineOmrani();
        if ( Carbon::parse($first_unpaid_monthly_Charge->ended_at)
                   ->diffInDays(now()) > 31 ) {
            return true;
        }

        return false;
    }

    public function getCanPayMonthlyChargesAttribute () {
        return $this->debts()
                    ->notPaid()
                    ->sum('amount') < Setting::getMinDebtAmount();
    }

    public function getCanPayHazineOmranisAttribute () {
        return $this->bedehiOmranis()
                    ->notPaid()
                    ->sum('amount') < 1;
    }

    public function hazineOmranis (): HasMany {
        return $this->hasMany(HazineOmrani::class , 'tenant_id');
    }

    public function bedehiOmranis (): HasMany {
        return $this->hasMany(BedehiOmrani::class , 'tenant_id');
    }

    public function canSeeMenu () {
        if ( $this->phone_number == null || $this->owner_first_name == null || $this->owner_last_name == null ) {
            return false;
        }

        return true;
    }

    public static function numbers_ar_fa ( $numbers ) {
        $find = [
            '٠' ,
            '١' ,
            '٢' ,
            '٣' ,
            '٤' ,
            '٥' ,
            '٦' ,
            '٧' ,
            '٨' ,
            '٩' ,
        ];
        $replace = [
            '۰' ,
            '۱' ,
            '۲' ,
            '۳' ,
            '۴' ,
            '۵' ,
            '۶' ,
            '۷' ,
            '۸' ,
            '۹' ,
        ];

        return (string)str_replace($find , $replace , $numbers);
    }

    public static function numbers_en_fa ( $numbers ) {
        $find = [
            '0' ,
            '1' ,
            '2' ,
            '3' ,
            '4' ,
            '5' ,
            '6' ,
            '7' ,
            '8' ,
            '9' ,
        ];
        $replace = [
            '۰' ,
            '۱' ,
            '۲' ,
            '۳' ,
            '۴' ,
            '۵' ,
            '۶' ,
            '۷' ,
            '۸' ,
            '۹' ,
        ];

        return (string)str_replace($find , $replace , $numbers);
    }

    public static function englishNumber ( $numbers ) {
        $arabic = [
            '٠' ,
            '١' ,
            '٢' ,
            '٣' ,
            '٤' ,
            '٥' ,
            '٦' ,
            '٧' ,
            '٨' ,
            '٩' ,
        ];
        $eng = [
            '0' ,
            '1' ,
            '2' ,
            '3' ,
            '4' ,
            '5' ,
            '6' ,
            '7' ,
            '8' ,
            '9' ,
        ];
        $result = str_replace($arabic , $eng , $numbers);
        $farsi = [
            '۰' ,
            '۱' ,
            '۲' ,
            '۳' ,
            '۴' ,
            '۵' ,
            '۶' ,
            '۷' ,
            '۸' ,
            '۹' ,
        ];

        return str_replace($farsi , $eng , $result);
    }

    public function other (): HasOne {
        return $this->hasOne(Other::class , 'tenant_id');
    }

    public function getOtherHasDebtAttribute () {
        return $this->other && $this->other->otherDebts()
                                           ->notPaid()
                                           ->count() > 0;
    }

    public function getOtherHasMonthlyChargeDueDatePassedAndNotPaidAttribute () {
        return $this->other && $this->other->otherMonthlyCharges()
                                           ->notPaid()
                                           ->dueDatePassed()
                                           ->count() > 0;
    }

    public function getHasPassedDueDateHazineOmraniAttribute () {
        return $this->hazineOmranis()->notPaid()->dueDatePassed()->count() > 0;
    }
}
