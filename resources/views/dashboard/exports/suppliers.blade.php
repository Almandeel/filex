<table>
    <thead>
    <tr>
        <th>الاسم</th>
        <th>رقم الهاتف</th>
    </tr>
    </thead>
    <tbody>
    @foreach($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->phone }}</td>
        </tr>
    @endforeach
    </tbody>
</table>