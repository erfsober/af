<table>
    <thead>
    <tr>
        <th>پلاک</th>
        <th>تعداد اخطار</th>
        <th>مبلغ شارژ عقب افتاده</th>
        <th>مبلغ بدهی</th>
        <th>مبلغ شارژ ثابت ماهیانه</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tenants as $tenant)
        <tr>
            <td>{{ $tenant->plaque }}</td>
            <td>{{ $tenant->warnings()->count() }}</td>
            <td>{{ number_format($tenant->passed_due_date_amount) }}</td>
            <td>{{ number_format($tenant->debts()->notPaid()->sum('amount')) }}</td>
            <td>{{ number_format($tenant->monthly_charge_amount) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
