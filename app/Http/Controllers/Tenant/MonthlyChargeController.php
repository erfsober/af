<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BedehiOmrani;
use App\Models\Debt;
use App\Models\HazineOmrani;
use App\Models\MonthlyCharge;
use App\Models\OwnershipDebt;
use Auth;
use Illuminate\Http\Request;

class MonthlyChargeController extends Controller {
    public function index ( Request $request ) {
        $tenant = Auth::guard('tenant')
                      ->user();
        $records = MonthlyCharge::query()
                                ->when($request->get('search') , function ( $query ) use ( $request ) {
                                    $query->where(function ( $q ) use ( $request ) {
                                        $q->where('id' , 'like' , '%' . $request->search . '%')
                                          ->orWhere('month' , 'like' , '%' . $request->search . '%');
                                    });
                                })
                                ->where('tenant_id' , $tenant->id)
            ->orderBy('id')
                                ->get();
        $debts = Debt::query()
                     ->where('tenant_id' , $tenant->id)
                     ->get();

        $bedehi_omranis = BedehiOmrani::query()
                                      ->where('tenant_id' , $tenant->id)
                                      ->get();

        $hazine_omranis = HazineOmrani::query()
                     ->where('tenant_id' , $tenant->id)
                     ->get();

        return view('metronic.tenant.monthly-charges.index' , compact('records' , 'bedehi_omranis','hazine_omranis', 'debts'));
    }
}
