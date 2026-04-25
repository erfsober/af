@extends('metronic.master')
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">
                        تعریف دوره مالی برای پلاک {{ $record->plaque }}
                    </h3>
                </div>

                <form id="my-form" method="post"
                      action="{{ route('admin.others.create-financial-period', $record->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">تاریخ شروع
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input data-jdp name="started_at" value="" type="text" class="form-control started-at-datepicker" />
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label class="col-form-label">تاریخ پایان
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input data-jdp name="ended_at" value="" type="text" class="form-control ended-at-datepicker" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- show otherFinancialPeriodLogs --}}
                            <div class="col-xl-12">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped">
                                        <thead class="thead-light iransans-web">
                                        <tr>
                                            <th class="iransans-web">تاریخ شروع</th>
                                            <th class="iransans-web">تاریخ پایان</th>
                                            <th class="iransans-web">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($record->otherFinancialPeriodLogs as $log)
                                            <tr>
                                                <td class="iransans-web">{{ verta($log->started_at)->format('Y/m/d') }}</td>
                                                <td class="iransans-web">{{ verta($log->ended_at)->format('Y/m/d') }}</td>
                                                <td class="iransans-web">{{ verta($log->created_at)->format('Y/m/d') }}</td>
                                                <td class="iransans-web">
                                                    <a href="{{ route('admin.others.delete-financial-period-log', $log->id) }}" class="btn btn-sm btn-danger">حذف</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end: Datatable-->
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success mr-2">تعریف دوره مالی به مبلغ ماهیانه {{ number_format($record->monthly_charge_amount) }} ریال</button>
                    </div>
                </form>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-custom">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">{{ "ایجاد بدهکاری برای پلاک " . $record->plaque }}
                                    <span class="text-muted pt-2 font-size-sm d-block"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">

                            <form class="m-form m-form--fit m-form--label-align-right" method="post" action="{{ route('admin.others.submit-bedehkari') }}">
                                @csrf
                                @method('POST')
                                <div class="m-portlet__body">
                                    <div class="form-group m-form__group">
                                        <label for="exampleInputEmail1">مبلغ بدهکاری</label>
                                        <input type="hidden" name="other_id" value="{{ $record->id }}">
                                        <input type="text" class="form-control m-input m-input--square" id="numberInput2" aria-describedby="emailHelp" placeholder="" name="amount">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="exampleInputEmail1">دلیل بدهکاری</label>
                                        <input type="text" class="form-control m-input m-input--square" aria-describedby="emailHelp" placeholder="" name="reason">
                                    </div>
                                </div>
                                <div class="m-portlet__foot m-portlet__foot--fit">
                                    <div class="m-form__actions">
                                        <button type="submit" class="btn btn-dark">ثبت بدهکاری</button>
                                    </div>
                                </div>
                            </form>
                            <!--end: Datatable-->
                        </div>
                    </div>

                </div>
                <div class="col-xl-6">
                    <div class="card card-custom">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">{{ "ایجاد بستانکاری برای پلاک " . $record->plaque }}
                                    <span class="text-muted pt-2 font-size-sm d-block"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">

                            <form class="m-form m-form--fit m-form--label-align-right" method="post" action="{{ route('admin.others.submit-bestankari') }}">
                                @csrf
                                @method('POST')
                                <div class="m-portlet__body">
                                    <div class="form-group m-form__group">
                                        <label for="exampleInputEmail1">مبلغ بستانکاری</label>
                                        <input type="hidden" name="other_id" value="{{ $record->id }}">
                                        <input type="text" class="form-control m-input m-input--square" id="numberInput" aria-describedby="emailHelp" placeholder="" name="amount">
                                        <span class="m-form__help">لطفا مبلغی کمتر یا مساوی با ({{ number_format($record->getFirstUnpaidMonthlyCharge()->amount ?? 0) }} ریال) وارد نمایید.</span>
                                    </div>
                                </div>
                                <div class="m-portlet__foot m-portlet__foot--fit">
                                    <div class="m-form__actions">
                                        <button type="submit" class="btn btn-success">ثبت بستانکاری</button>
                                    </div>
                                </div>
                            </form>
                            <!--end: Datatable-->
                        </div>
                    </div>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-custom">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">بدهی های پلاک {{ $record->plaque }}
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
                                    @foreach($record->otherDebts()->orderBy('id')->get() as $debt)
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
                                                    <a class="btn btn-sm btn-danger" href="{{ route('admin.others.remove-bedehkari', $debt->id) }}">حذف بدهی</a>
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
                </div>
                <div class="col-xl-6">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                شارژ های ماهیانه پلاک {{ $record->plaque }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">

                                    <!--begin::Section-->
                                    <div class="m-section">
                                        <span class="m-section__sub">
                                        </span>
                                        <div class="m-section__content">
                                            <table class="table table-sm m-table m-table--head-bg-brand">
                                                <thead class="thead-inverse">
                                                <tr>
                                                    <th>موعد پرداخت</th>
                                                    <th>مبلغ</th>
                                                    <th>وضعیت پرداخت</th>
                                                    <th>عملیات</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($record->otherMonthlyCharges()->orderBy('due_date')->get() as $omc)
                                                    <tr>
                                                        <td>{{ verta($omc->due_date)->format('Y/m/d') }}</td>
                                                        <td>{{ number_format($omc->amount) }} ریال</td>
                                                        <td>
                                                            @if($omc->paid_at)
                                                                <span class="label label-inline label-light-success">پرداخت موفق</span>
                                                            @else
                                                                <span class="label label-inline label-light-danger">پرداخت نشده</span>
                                                            @endif
                                                        </td>
                                                        <td class="iransans-web">
                                                            @if($omc->paid_via == \App\Models\OtherMonthlyCharge::PAID_VIA['ADMIN'])
                                                                <a class="btn btn-sm btn-danger" href="{{ route('admin.others.restore-monthly-charge', $omc->id) }}">بازگردانی</a>
                                                            @endif
                                                            {{-- delete --}}
                                                            <a class="btn btn-sm btn-danger" href="{{ route('admin.others.remove-monthly-charge', $omc->id) }}">حذف</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--end::Section-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const convertToEnglishDigits = (value) => {
                const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

                return value
                    .replace(/[\u06F0-\u06F9]/g, (char) => englishDigits[persianDigits.indexOf(char)])
                    .replace(/[\u0660-\u0669]/g, (char) => englishDigits[char.charCodeAt(0) - 0x0660]);
            };

            const formatNumericInput = (input) => {
                if (!input) {
                    return;
                }

                input.addEventListener('input', (event) => {
                    let value = event.target.value;
                    value = convertToEnglishDigits(value);
                    value = value.replace(/,/g, '');
                    value = value.replace(/[^\d]/g, '');

                    if (value === '') {
                        event.target.value = '';
                        return;
                    }

                    event.target.value = Number(value).toLocaleString();
                });
            };

            formatNumericInput(document.getElementById('numberInput'));
            formatNumericInput(document.getElementById('numberInput2'));
        });
    </script>
@endpush
