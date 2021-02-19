@extends('layouts.dashboard.report')

@section('name')
    تقرير المنتجات
@endsection


@section('content')
<div class="reportr-header">
    <div>
        <div class="form-group">
            عدد المنتجات : {{ count($items) }}
        </div>
    </div>
</div>
<div class="report-body">
    <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>اسم المنتج</th>
            <th>الوحدة</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($items as $index=>$item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                        @foreach ($item->units as $unit)
                            <span>{{ $unit->name }}</span>
                        @endforeach
                    </td>
            </tr>
            @endforeach
        </tbody>
      </table>
</div>

<div class="report-footer">
</div>
@endsection