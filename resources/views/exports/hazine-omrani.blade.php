<table>
    <thead>
    <tr>
        <th>پلاک</th>
        <th>مبلغ هزینه عمرانی پرداخت شده</th>
        <th>مبلغ هزینه عمرانی سر رسید شده بدون تخفیف و جریمه</th>
        <th>کل</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tenants as $tenant)
        <tr>
            <td>{{ $tenant->plaque }}</td>
            <td>{{ number_format($tenant->hazineOmranis->whereNotNull('paid_at')->sum('paid_amount')) }}</td>
            <td>{{ number_format($tenant->hazineOmranis()->dueDatePassed()->notPaid()->sum('original_amount')) }}</td>
            <td>{{ number_format($tenant->hazineOmranis->sum('original_amount')) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
