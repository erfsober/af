@extends('metronic.master')
@section('content')
    @php
        $model_name = 'تراکنش';
    @endphp
    <div>
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">{{ $model_name }} ها
                                <span class="text-muted pt-2 font-size-sm d-block"></span>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Search Form-->
                        <!--begin::Search Form-->
                        <div class="mb-7">
                            <form action="{{ route('tenant.transactions.index') }}" method="get">
                                <div class="row align-items-center">
                                    <div class="col-lg-3 col-xl-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-12 col-xl-12 my-2 my-md-0">
                                                <div class="input-icon">
                                                    <input name="search" type="text" class="form-control"
                                                           placeholder="جستجو..." value="{{ request()->search }}"/>
                                                    <span>
                                                        <i class="flaticon2-search-1 text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                        <button class="btn btn-light-primary px-6 font-weight-bold">اعمال فیلتر</button>
                                    </div>
                                </div>
                            </form>
                        </div>



                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped">
                                <caption>{{$model_name}} ها</caption>
                                <thead class="thead-light iransans-web">
                                <tr>
                                    <th class="iransans-web">شناسه</th>
                                    <th class="iransans-web">شماره تراکنش</th>
                                    <th class="iransans-web">کد پیگیری</th>
                                    <th class="iransans-web">کاربر</th>
                                    <th class="iransans-web">موضوع پرداخت</th>
                                    <th class="iransans-web">مبلغ</th>
                                    <th class="iransans-web">وضعیت پرداخت</th>
                                    <th class="iransans-web">تاریخ ایجاد</th>
                                    <th class="iransans-web">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td class="iransans-web">{{ $record->id }}</td>
                                        <td class="iransans-web">{{ $record->tx_id }}</td>
                                        <td class="iransans-web">{{ $record->ref_id }}</td>
                                        <td class="iransans-web">{{ $record->tenant->plaque }}</td>
                                        <td class="iransans-web">{{ $record->subject }}</td>
                                        <td class="iransans-web">{{ number_format($record->amount) }} ریال</td>
                                        <td class="iransans-web">
                                            {{ $record->status }}
                                            <br>
                                            @if($record->paid_at)
                                                @php $daysDiff = $record->paidSoonerOrLater(); @endphp
                                                @if($daysDiff < 0)
                                                    <span class="badge badge-success">{{ abs($daysDiff) }} روز زودتر</span>
                                                @elseif($daysDiff == 0)
                                                    <span class="badge badge-info">به موقع</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $daysDiff }} روز دیرتر</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="iransans-web">{{ verta($record->created_at)->format('Y/m/d H:i:s') }}</td>
                                        <td class="iransans-web">
                                            @if($record->paid_at)
                                                <a href="{{ route('tenant.transactions.pdf', $record->id) }}" class="btn btn-primary">نمایش PDF</a>
                                                <a class="btn btn-outline-success" href="https://telegram.me/share/url?url={{ route('download-pdf', $record->tx_id) }}&text=آفتاب فارس">اشتراک گذاری در تلگرام</a>
                                                <a class="btn btn-outline-success" href="whatsapp://send?text={{ route('download-pdf', $record->tx_id) }}" data-action="share/whatsapp/share">اشتراک گذاری در واتساپ</a>
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

@endsection
@push('scripts')

@endpush
