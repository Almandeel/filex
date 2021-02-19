<table>
    <thead>
    <tr>
        <th>الاسم</th>
        <th>رقم الهاتف</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->phone }}</td>
        </tr>
    @endforeach
    </tbody>
</table>