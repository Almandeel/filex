@extends('layouts.dashboard.app', ['modals' => ['expenses']])

@section('title')
    عرض
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الصروفات','عرض'])
        @slot('url', [route('expenses.index'), '#'])
        @slot('icon', ['dollar', 'eye'])
    @endcomponent
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>الصروفات</h3>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>القيمة</th>
                                <td>{{ $expense->amount }}</td>
                            </tr>
                            <tr>
                                <th>التفاصيل</th>
                                <td>{{ $expense->details }}</td>
                            </tr>
                            <tr>
                                <th>التاريخ</th>
                                <td>{{ $expense->created_at->format('y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @permission('expenses-delete')
                            <a class="btn btn-warning btn-xs showexpensesModal  update" data-action="{{ route('expenses.update', $expense->id) }}" data-amount="{{ $expense->amount }}" data-details="{{ $expense->details }}"><i class="fa fa-edit"></i> تعديل </a>
                        @endpermission
                    </div>
                </div>
            </div>
        </div>
@endsection