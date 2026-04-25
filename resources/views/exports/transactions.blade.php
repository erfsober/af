<table>
    <thead>
    <tr>
        <th>موضوع</th>
        <th>پلاک</th>
        <th>شماره سفارش</th>
        <th>تاریخ و ساعت</th>
        <th>مبلغ اصلی</th>
        <th>مبلغ پرداختی</th>
        <th>مبلغ تخفیف</th>
        <th>مبلغ جریمه</th>
        <th>درصد تخیف</th>
        <th>درصد جریمه</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->subject }}</td>
            <td>{{ $transaction?->tenant?->plaque }} {{ $transaction?->other?->plaque }}</td>
            <td>{{ $transaction->ref_id }}</td>
            <td>{{ verta($transaction->paid_at)->format('Y-m-d H:i:s') }}</td>
            <td>{{ number_format($transaction->original_amount) }}</td>
            <td>{{ number_format($transaction->amount) }}</td>
            <td>{{ number_format($transaction->discountAmount()) }}</td>
            <td>{{ number_format($transaction->penaltyAmount()) }}</td>
            <td>{{ $transaction->discountPercent() }}</td>
            <td>{{ $transaction->penaltyPercent() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
