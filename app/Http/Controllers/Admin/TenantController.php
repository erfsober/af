<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DidNotPayMonthlyChargeExport;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\BedehiOmrani;
use App\Models\Debt;
use App\Models\HazineOmrani;
use App\Models\MonthlyCharge;
use App\Models\OwnershipDebt;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Services\Convert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TenantController extends Controller {
    public function index ( Request $request ) {
        $records = Tenant::query()
                         ->with([
                                    'tenantType' ,
                                    'floor' ,
                                ])
                         ->withCount([ 'warnings' ])
                         ->when($request->get('search') , function ( Builder $query ) use ( $request ) {
                             $search = $request->get('search');
                             $query->where('id' , 'like' , '%' . $search . '%')
                                   ->orWhere('name' , 'like' , '%' . $search . '%')
                                   ->orWhere('plaque' , 'like' , '%' . $search . '%')
                                   ->orWhere('owner_first_name' , 'like' , '%' . $search . '%')
                                   ->orWhere('owner_last_name' , 'like' , '%' . $search . '%')
                                   ->orWhere('username' , 'like' , '%' . $search . '%');
                         })
                         ->orderByDesc('id')
                         ->get();

        return view('metronic.admin.tenants.index' , compact('records'));
    }

    public function exportDidNotPayMonthlyCharge ( Request $request ) {
        $request->validate([
                               'month' => [ 'required' ] ,
                           ]);

        return Excel::download(new DidNotPayMonthlyChargeExport($request->get('month')) , 'bedehkaran-charge.xlsx');
    }

    public function create () {
        return view('metronic.admin.tenants.form');
    }

    public function store ( Request $request ) {
        $record = new Tenant();
        $this->save($record , $request);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('رکورد با موفقیت ایجاد شد.' , 'تبریک!');

        return redirect()->route('admin.tenants.index');
    }

    public function edit ( $id ) {
        $record = Tenant::query()
                        ->findOrFail($id);

        return view('metronic.admin.tenants.form' , compact('record'));
    }

    public function update ( Request $request , $id ) {
        $record = Tenant::query()
                        ->findOrFail($id);
        $this->save($record , $request);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('رکورد با موفقیت بروز شد.' , 'تبریک!');

        return redirect()->route('admin.tenants.index');
    }

    public function save ( Tenant $record , Request $request ) {
        $request->validate([
                               'plaque' => [ 'required' ] ,
                               'floor_id' => [ 'required' ] ,
                               'tenant_type_id' => [ 'required' ] ,
                               'monthly_charge_amount' => [
                                   'required' ,
                                   'numeric' ,
                               ] ,
                           ]);
        $record->plaque = $request->get('plaque');
        $record->name = $request->get('name');
        $record->owner_first_name = $request->get('owner_first_name');
        $record->owner_last_name = $request->get('owner_last_name');
        $record->phone_number = $request->get('phone_number');
        $record->floor_id = $request->get('floor_id');
        $record->tenant_type_id = $request->get('tenant_type_id');
        $record->meters = $request->get('meters');
        $record->monthly_charge_amount = $request->get('monthly_charge_amount');
        $record->activity_type = $request->get('activity_type');
        $record->save();
        if ( $password = $request->get('password') ) {
            $record->password = bcrypt($password);
        }
        if ( $request->hasFile('image') ) {
            $record->addMediaFromRequest('image')
                   ->toMediaCollection('image');
        }
        $record->save();
    }

    public function monthlyCharges ( Request $request , $id ) {
        $tenant = Tenant::query()
                        ->findOrFail($id);
        $records = MonthlyCharge::query()
                                ->when($request->get('search') , function ( $query ) use ( $request ) {
                                    $query->where(function ( $q ) use ( $request ) {
                                        $q->where('id' , 'like' , '%' . $request->search . '%')
                                          ->orWhere('month' , 'like' , '%' . $request->search . '%');
                                    });
                                })
                                ->where('tenant_id' , $id)
                                ->orderBy('id')
                                ->get();
        $debts = Debt::query()
                     ->where('tenant_id' , $tenant->id)
                     ->get();
        $bedehi_omranis = BedehiOmrani::query()
                                        ->where('tenant_id' , $tenant->id)
                                        ->get();

        $hazine_omranis = HazineOmrani::query()
            ->where('tenant_id', $tenant->id)
            ->get();

        return view('metronic.admin.tenants.monthly-charges.index' , compact('hazine_omranis','tenant' , 'records' , 'debts', 'bedehi_omranis'));
    }

    public function setDefaultPassword ( $id ) {
        $tenant = Tenant::query()
                        ->findOrFail($id);
        $tenant->password = bcrypt($tenant->default_password);
        $tenant->save();
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('رکورد با موفقیت بروز رسانی شد.' , 'تبریک!');

        return redirect()->route('admin.tenants.index');
    }

    public function submitBestankari ( Request $request ) {
        $request->validate([
                               'amount' => [ 'required' ] ,
                               'tenant_id' => [ 'required' ] ,
                           ]);
        $tenant = Tenant::query()
                        ->findOrFail($request->get('tenant_id'));
        $amount = str_replace(',' , '' , $request->get('amount'));
        $result = $tenant->submitBestankari($amount);
        if ( $result ) {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addSuccess('بستانکاری با موفقیت اعمال شد و از شارژ ماهیانه کاسته شد.' , 'تبریک');

            return redirect()->back();
        }
        else {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addError('مبلغ بیش از حد مجاز' , 'خطا');

            return redirect()->back();
        }
    }
    public function submitBestankariForHazineOmrani ( Request $request ) {
        $request->validate([
                               'amount' => [ 'required' ] ,
                               'tenant_id' => [ 'required' ] ,
                           ]);
        $tenant = Tenant::query()
                        ->findOrFail($request->get('tenant_id'));
        $amount = str_replace(',' , '' , $request->get('amount'));
        $result = $tenant->submitBestankariForHazineOmrani($amount);
        if ( $result ) {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addSuccess('بستانکاری با موفقیت اعمال شد و از هزینه عمرانی کاسته شد.' , 'تبریک');

            return redirect()->back();
        }
        else {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addError('مبلغ بیش از حد مجاز' , 'خطا');

            return redirect()->back();
        }
    }

    public function restoreMonthlyCharge ( $id ) {
        $monthly_charge = MonthlyCharge::query()
                                       ->findOrFail($id);
        if ( $monthly_charge->paid_via != MonthlyCharge::PAID_VIA[ 'ADMIN' ] ) {

            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addSuccess('شارژی که از طریق به پرداخت انجام شده باشد قابلیت بازگشت ندارد.' , 'خطا');

            return redirect()->back();
        }
        Transaction::query()
                   ->where('monthly_charge_id' , $monthly_charge->id)
                   ->delete();
        $monthly_charge->paid_via = null;
        $monthly_charge->original_amount = $monthly_charge->tenant->monthly_charge_amount;
        $monthly_charge->paid_amount = 0;
        $monthly_charge->paid_at = null;
        $monthly_charge->save();
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('شارژ ماهیانه به حالت اولیه بازگشت.' , 'تبریک');

        return redirect()->back();
    }
    public function restoreHazineOmrani ( $id ) {
        $hazine_omrani = HazineOmrani::query()
                                       ->findOrFail($id);
        if ( $hazine_omrani->paid_via != MonthlyCharge::PAID_VIA[ 'ADMIN' ] ) {

            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addSuccess('شارژی که از طریق به پرداخت انجام شده باشد قابلیت بازگشت ندارد.' , 'خطا');

            return redirect()->back();
        }
        Transaction::query()
                   ->where('hazine_omrani_id' , $hazine_omrani->id)
                   ->delete();
        $hazine_omrani->paid_via = null;
        $hazine_omrani->original_amount = $hazine_omrani->for_restore_amount;
        $hazine_omrani->paid_amount = 0;
        $hazine_omrani->paid_at = null;
        $hazine_omrani->save();
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('هزینه عمرانی به حالت اولیه بازگشت.' , 'تبریک');

        return redirect()->back();
    }

    public function submitBedehkari ( Request $request ) {
        $request->validate([
                               'amount' => [ 'required' ] ,
                               'tenant_id' => [ 'required' ] ,
                               'reason' => [ 'required' ] ,
                           ]);
        $tenant = Tenant::query()
                        ->findOrFail($request->get('tenant_id'));
        $amount = str_replace(',' , '' , $request->get('amount'));
        $reason = $request->get('reason');
        $tenant->addDebt($amount , $reason , Debt::TYPES[ 'NORMAL' ]);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی ایجاد شد.' , 'تبریک');

        return redirect()->back();
    }

    public function removeBedehkari ( Request $request , $id ) {
        $debt = Debt::query()
                    ->findOrFail($id);
        $debt->tenant->removeDebt($id);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی حذف شد.' , 'تبریک');

        return redirect()->back();
    }

    public function submitOwnershipDebt ( Request $request ) {
        $request->validate([
                               'amount' => [ 'required' ] ,
                               'tenant_id' => [ 'required' ] ,
                               'due_date' => [ 'required' ] ,
                           ]);
        $tenant = Tenant::query()
                        ->findOrFail($request->get('tenant_id'));
        $amount = str_replace(',' , '' , $request->get('amount'));
        $amount = Convert::convertToEnNumbers($amount);
        OwnershipDebt::query()
                     ->create([
                                  'amount' => $amount ,
                                  'tenant_id' => $request->get('tenant_id') ,
                                  'due_date' => Carbon::createFromTimestamp($request->get('due_date'))
                                                      ->endOfDay() ,
                              ]);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی ایجاد شد.' , 'تبریک');

        return redirect()->back();
    }

    public function removeOwnershipDebt ( Request $request , $id ) {
        $ownership_debt = OwnershipDebt::query()
                                       ->findOrFail($id);
        if ( $ownership_debt->paid_at ) {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addError('بدهی پرداخت شده قابل حذف نمیباشد' , 'خطا');

            return redirect()->back();
        }
        $ownership_debt->delete();
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی حذف شد.' , 'تبریک');

        return redirect()->back();
    }

    ###

    public function submitBedehiOmrani ( Request $request ) {
        $request->validate([
                               'amount' => [ 'required' ] ,
                               'tenant_id' => [ 'required' ] ,
                           ]);
        $tenant = Tenant::query()
                        ->findOrFail($request->get('tenant_id'));
        $amount = str_replace(',' , '' , $request->get('amount'));
        $amount = Convert::convertToEnNumbers($amount);
        BedehiOmrani::query()
                     ->create([
                                  'amount' => $amount ,
                                  'tenant_id' => $request->get('tenant_id') ,
                              ]);
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی ایجاد شد.' , 'تبریک');

        return redirect()->back();
    }

    public function removeBedehiOmrani ( Request $request , $id ) {
        $debt = BedehiOmrani::query()
                                       ->findOrFail($id);
        if ( $debt->paid_at ) {
            flash()
                ->options([
                              'timeout' => 3000 ,
                              'position' => 'top-left' ,
                          ])
                ->addError('بدهی پرداخت شده قابل حذف نمیباشد' , 'خطا');

            return redirect()->back();
        }
        $debt->delete();
        flash()
            ->options([
                          'timeout' => 3000 ,
                          'position' => 'top-left' ,
                      ])
            ->addSuccess('بدهی حذف شد.' , 'تبریک');

        return redirect()->back();
    }
}
