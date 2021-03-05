@extends('layouts.dashboard.app', ['modals' => ['expenses'], 'datatable' => true])

@section('title')
    المنصرفات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المنصرفات'])
        @slot('url', ['#'])
        @slot('icon', ['dollar'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            @permission('expenses-create')
                <button type="button" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right showexpensesModal create" data-toggle="modal" data-target="#expenses">
                    <i class="fa fa-plus"> إضافة</i>
                </button>
            @endpermission
        </div>
        <div class="box-body">
            <table id="expenses-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نوع المنصرف</th>
                        <th>القيمة</th>
                        <th>التفاصيل</th>
                        <th>الخزنة</th>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $total = 0;
                    @endphp
                    @foreach ($expenses as $index=>$expense)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $expense->expensesType->name }}</td>
                            <td>{{ $expense->amount }}</td>
                            <td>{{ str_limit($expense->details, 30) }}</td>
                            <td>
                                {{ $expense->safe->name }}
                            </td>
                            <td>{{ $expense->user->employee->name }}</td>
                            <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    @permission('expenses-read')
                                        <a class="btn btn-info btn-xs" href="{{ route('expenses.show', $expense->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    @endpermission
    
                                    @permission('expenses-update')
                                        <a class="btn btn-warning btn-xs showexpensesModal  update" data-action="{{ route('expenses.update', $expense->id) }}" data-amount="{{ $expense->amount }}" data-details="{{ $expense->details }}" data-type="{{ $expense->expenses_type }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission
                                </div>
                                
                                @permission('expenses-delete')
                                <form style="display:inline-block" action="{{ route('expenses.destroy', $expense->id) }}" method="post">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                        @php 
                            $total += $expense->amount;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الاجمالى</th>
                        <th></th>
                        <th>{{ number_format($total, 2) }}</th>
                        <th colspan="6"></th>
                    </tr>
                </tfoot>
            </table>

            <input type="hidden" data-action="{{ route('expenses.store') }}" id="create">
        </div>
    </div>
@endsection
