<table>
    <thead>
    <tr>
        <th>پلاک</th>
        <th>توضیحات</th>
        <th>مبلغ شارژ عقب افتاده</th>
        <th>مبلغ بدهی</th>
        <th>مبلغ شارژ ثابت ماهیانه</th>
    </tr>
    </thead>
    <tbody>
    @foreach($others as $other)
        @php
        /* @var \App\Models\Other $other */
        @endphp
        <tr>
            <td>{{ $other->plaque }}</td>
            <td>{{ $other->description }}</td>
            <td>{{ $other->passed_due_date_amount }}</td>
            <td>{{ $other->otherDebts()->notPaid()->sum('amount') }}</td>
            <td>{{ $other->monthly_charge_amount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
