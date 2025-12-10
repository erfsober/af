@php use App\Models\Setting; @endphp
@extends('metronic.master')
@php
    $tenant = Auth::guard('tenant')->user();
@endphp
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container-fluid">
                    <div class="card card-custom">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">بدهی های پلاک {{ $tenant->plaque }}
                                    <span class="text-muted pt-2 font-size-sm d-block"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped">
                                    <thead class="thead-light iransans-web">
                                    <tr>
                                        <th class="iransans-web">دلیل بدهی</th>
                                        <th class="iransans-web">مبلغ</th>
                                        <th class="iransans-web">وضعیت</th>
                                        <th class="iransans-web">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($debts as $debt)
                                        <tr>
                                            <td class="iransans-web">{{ $debt->reason }}</td>
                                            <td class="iransans-web">{{ number_format($debt->amount) }} ریال</td>
                                            <td class="iransans-web">
                                                @if($debt->paid_at)
                                                    <span class="label label-inline label-light-success">پرداخت موفق</span>
                                                @else
                                                    <span class="label label-inline label-light-danger">پرداخت نشده</span>
                                                @endif
                                            </td>
                                            <td class="iransans-web">
                                                @if(!$debt->paid_at)
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#debt-modal-{{ $debt->id }}">
                                                        پرداخت بدهی
                                                    </button>




                                                    {{--{{ route('tenant.transaction.generate-url', ['debt_id' => $debt->id]) }}--}}
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end: Datatable-->
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Container-->
            </div>
            <hr>
            <br>
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container-fluid">
                    <div class="card card-custom">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">شارژ های ماهیانه پلاک {{ $tenant->plaque }}
                                    <span class="text-muted pt-2 font-size-sm d-block"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($tenant->has_passed_due_date_hazine_omrani)
                                <div class="m-alert m-alert--icon alert alert-danger" role="alert">
                                    <div class="m-alert__icon">
                                        <i class="flaticon-danger"></i>
                                    </div>
                                    <div class="m-alert__text">
                                        <strong>تخفیف شما غیر فعال شد</strong>
                                        کاربر گرامی به دلیل هزینه عمرانی پرداخت نشده ، تخفیف شما غیر فعال است.
                                    </div>
                                </div>
                            @endif

                            @if($tenant->other_has_debt)
                                <div class="m-alert m-alert--icon alert alert-danger" role="alert">
                                    <div class="m-alert__icon">
                                        <i class="flaticon-danger"></i>
                                    </div>
                                    <div class="m-alert__text">
                                        <strong>تخفیف شما غیر فعال شد</strong>
                                        کاربر گرامی به دلیل بدهی واحد متفرقه تخفیف شما غیر فعال است.
                                    </div>
                                </div>
                            @endif

                            @if($tenant->other_has_monthly_charge_due_date_passed_and_not_paid)
                                <div class="m-alert m-alert--icon alert alert-danger" role="alert">
                                    <div class="m-alert__icon">
                                        <i class="flaticon-danger"></i>
                                    </div>
                                    <div class="m-alert__text">
                                        <strong>تخفیف شما غیر فعال شد</strong>
                                        کاربر گرامی به دلیل شارژ پرداخت نشده واحد متفرقه تخفیف شما غیر فعال است.
                                    </div>
                                </div>
                            @endif


                            @if(!$tenant->can_pay_monthly_charges)
                                <div class="m-alert m-alert--icon alert alert-danger" role="alert">
                                    <div class="m-alert__icon">
                                        <i class="flaticon-danger"></i>
                                    </div>
                                    <div class="m-alert__text">
                                        <strong>اخطار بدهی</strong>
                                        کاربر گرامی شما مبلغ {{ number_format($tenant->debts()->notPaid()->sum('amount')) }} ریال بدهکار هستید.
                                        <br>
                                        لطفا جهت پرداخت شارژ های ماهیانه، ابتدا بدهی خود را تسویه نمایید
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped">
                                    <thead class="thead-light iransans-web">
                                    <tr>
                                        <th class="iransans-web">ماه</th>
                                        <th class="iransans-web">موعد پرداخت</th>
                                        <th class="iransans-web">هزینه</th>
                                        <th class="iransans-web">وضعیت</th>
                                        <th class="iransans-web">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="iransans-web"> {{ $record->financial_month_name }}  ماه</td>
                                            <td class="iransans-web">{{ verta($record->due_date)->format('Y/m/d') }}</td>
                                            <td class="iransans-web">
                                                پایه: {{ number_format($record->original_amount) }} ریال
                                                @if(!$record->paid_at && $record->original_amount > $record->final_amount)
                                                    <hr>
                                                    <span class="text-success"> پس از تخفیف: {{ number_format($record->final_amount) }} ریال</span>
                                                @endif
                                                @if(!$record->paid_at && $record->original_amount < $record->final_amount)
                                                    <hr>
                                                    <span class="text-danger"> با جریمه: {{ number_format($record->final_amount) }} ریال</span>
                                                @endif
                                                @if($record->paid_amount)
                                                    <hr>
                                                پرداختی شما: {{ number_format($record->paid_amount) }} ریال
                                                @endif
                                            </td>
                                            <td class="iransans-web">
                                                @if($record->paid_at)
                                                    <span class="label label-inline label-light-success">پرداخت موفق</span>
                                                @else
                                                    <span class="label label-inline label-light-danger">پرداخت نشده</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$tenant->can_pay_monthly_charges)
                                                    ابتدا بدهی خود را تسویه نمایید
                                                @elseif($record->id != @$tenant->getFirstUnpaidMonthlyCharge()->id)
                                                    --
                                                @elseif($record->id != @$tenant->getFirstUnpaidMonthlyCharge()->id && @$tenant->oneMonthPassedFromFirstUnpaidMonthlyCharge())
                                                    ابتدا شارژ ماه قبل را تسویه نمایید
                                                @else
                                                    @if(!$record->paid_at)
                                                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                                            <a href="{{ route('tenant.transaction.choose-gateway', ['generate_url' => route('tenant.transaction.generate-url', ['monthly_charge_id' => $record->id])]) }}" class="btn btn-sm btn-success">پرداخت</a>
                                                        </div>
                                                    @else
                                                    @endif
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end: Datatable-->
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Container-->
            </div>
        </div>
    </div>
    @foreach($debts as $debt)
        <!-- The Modal -->
        <div class="modal" id="debt-modal-{{ $debt->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="my-form" method="post"
                          action="{{ route('tenant.transaction.generate-url') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">پرداخت بدهی</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label class="col-form-label">مبلغ پرداختی به ریال</label>
                                    <input autocomplete="off" type="text" class="form-control" name="debt_amount"
                                           placeholder="مبلغ پرداختی به ریال را وارد نمایید"
                                           value=""/>
                                    <input type="hidden" name="debt_id" value="{{ $debt->id }}">
                                </div>
                                <span class="text-primary">حداکثر مبلغ قابل پرداخت {{ number_format($debt->amount) }} ریال میباشد</span>
                            </div>
                            {{-- radio button for gatway ( pasargard or behpardakht ) --}}
                            <div class="form-group mt-3">
                                <label class="col-form-label">انتخاب درگاه پرداخت</label>
                                <div>
                                    <div class="form-check form-check-inline align-items-center d-flex">
                                        <input class="form-check-input" type="radio" name="gateway" id="pasargad" value="pasargad" checked>
                                        <label class="form-check-label d-flex align-items-center ms-2" for="pasargad">
                                            <img src="{{ asset('seeds/icons/pasargad.jpg') }}" alt="پاسارگاد" style="height: 55px; margin-left: 6px;">
                                            پاسارگاد
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline align-items-center d-flex">
                                        <input class="form-check-input" type="radio" name="gateway" id="mellat" value="mellat">
                                        <label class="form-check-label d-flex align-items-center ms-2" for="mellat">
                                            <img src="{{ asset('seeds/icons/mellat.png') }}" alt="ملت" style="height: 55px; margin-left: 6px;">
                                            ملت
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">رفتن به درگاه</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach




@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const numberInput = document.getElementById('numberInput');

            const persianToEnglish = (str) => {
                const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                return str.replace(/[\u06F0-\u06F9]/g, (match) => {
                    return englishNumbers[persianNumbers.indexOf(match)];
                }).replace(/[\u0660-\u0669]/g, (match) => {
                    return englishNumbers[match.charCodeAt(0) - 0x0660];
                });
            };

            numberInput.addEventListener('input', (event) => {
                let value = event.target.value;
                value = persianToEnglish(value);
                value = value.replace(/,/g, '');
                if (!isNaN(value)) {
                    event.target.value = Number(value).toLocaleString();
                }
            });

        });

    </script>
@endpush
