@extends('layouts.dashboard.app', ['modals' => ['safe'], 'datatable' => true])

@section('title')
    الخزن
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الخزن'])
        @slot('url', ['#'])
        @slot('icon', ['money'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h4 class="box-title">
                <i class="fa fa-money"></i>
                <span>الخزن</span>
            </h4>
            @permission('safes-create')
                <div class="box-tools">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary btn-sm showSafeModal create" data-toggle="modal"
                            data-target="#safes">
                            <i class="fa fa-plus"> إضافة</i>
                        </button>
                    </div>
                </div>
            @endpermission
        </div>
        <div class="box-body">
            <table id="safes-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th rowspan="2">المعرف</th>
                        <th rowspan="2">الاسم</th>
                        <th rowspan="2">النوع</th>
                        <th rowspan="2">الرصيد</th>
                        <th colspan="4">الظهور</th>
                        <th rowspan="2">الخيارات</th>
                    </tr>
                    <tr>
                        <th>المشتريات</th>
                        <th>المبيعات</th>
                        <th>المنصرفات</th>
                        <th>الشيكات</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $total = 0;
                    @endphp
                    @foreach ($safes as $safe)
                        <tr>
                            <td>{{ $safe->id }}</td>
                            <td>{{ $safe->name }}</td>
                            <td>{{ $safe->getType() }}</td>
                            <td>{{ number_format($safe->balance(), 2) }}</td>
                            {{--  <td>
                                @if ($safe->show_bills)
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i>
                                        <span>المشتريات</span>
                                    </span>
                                @endif
                                @if ($safe->show_invoices)
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i>
                                        <span>المبيعات</span>
                                    </span>
                                @endif
                                @if ($safe->show_expenses)
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i>
                                        <span>المنصرفات</span>
                                    </span>
                                @endif
                                @if ($safe->show_cheques)
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i>
                                        <span>الشيكات</span>
                                    </span>
                                @endif
                            </td>  --}}
                            <td><input type="checkbox" {{ $safe->show_bills ? 'checked' : '' }} data-action="{{ route('safes.update', $safe) }}" data-column="show_bills" class="@permission('safes-update') update-show @endpermission" /></td>
                            <td><input type="checkbox" {{ $safe->show_invoices ? 'checked' : '' }} data-action="{{ route('safes.update', $safe) }}" data-column="show_invoices" class="@permission('safes-update') update-show @endpermission" /></td>
                            <td><input type="checkbox" {{ $safe->show_expenses ? 'checked' : '' }} data-action="{{ route('safes.update', $safe) }}" data-column="show_expenses" class="@permission('safes-update') update-show @endpermission" /></td>
                            <td><input type="checkbox" {{ $safe->show_cheques ? 'checked' : '' }} data-action="{{ route('safes.update', $safe) }}" data-column="show_cheques" class="@permission('safes-update') update-show @endpermission" /></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Options Buttons">
                                @permission('safes-read')
                                    <a class="btn btn-info btn-xs" href="{{ route('safes.show', $safe->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    
                                    <a class="btn btn-default btn-xs showSafeModal preview" 
                                        data-balance="{{ $safe->account->balance() }}" 
                                        data-id="{{ $safe->id }}" 
                                        data-opening-balance="{{ $safe->opening_balance }}"
                                        data-name="{{ $safe->name }}"
                                        data-type="{{ $safe->getType() }}"
                                    >
                                        <i class="fa fa-list"></i>
                                        <span>تفاصيل</span>
                                    </a>
                                @endpermission

                                @permission('safes-update')
                                    <button type="button" 
                                        class="btn btn-warning btn-xs showSafeModal update" 
                                        data-action="{{ route('safes.update', $safe->id) }}" 
                                        data-id="{{ $safe->id }}" 
                                        data-name="{{ $safe->name }}"
                                        data-type="{{ $safe->type }}"
                                        data-opening-balance="{{ $safe->opening_balance }}" 
                                        data-show-bills="{{ $safe->show_bills }}"
                                        data-show-invoices="{{ $safe->show_invoices }}"
                                        data-show-expenses="{{ $safe->show_expenses  }}" 
                                        data-show-cheques="{{ $safe->show_cheques }}"
                                    ><i class="fa fa-edit"></i> تعديل </button>
                                @endpermission
                                </div>

                                @permission('safes-delete')
                                    <a class="btn btn-danger btn-xs showSafeModal confirm-delete" data-balance="{{ $safe->account->balance() }}"
                                        data-id="{{ $safe->id }}" data-name="{{ $safe->name }}" 
                                        data-phone="{{ $safe->phone }}"
                                        data-action="{{ route('safes.destroy', $safe) }}"
                                    >
                                        <i class="fa fa-times"></i>
                                        <span>حذف</span>
                                    </a>
                                @endpermission
                            </td>
                        </tr>

                        @php 
                            $total += $safe->balance();
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">الاجمالي</th>
                        <th>{{ number_format($total, 2) }}</th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>

            <input type="hidden" data-action="{{ route('safes.store') }}" id="create" />
        </div>
    </div>
    @permission('safes-update')
    <form id="show-update" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="column" />
        <input type="hidden" name="status" />
        <input type="hidden" name="update_show" value="true"/>
    </form>
    @endpermission
@endsection
@push('js')
    <script>
        $(function(){
            @permission('safes-update')
            $(document).on('change', 'input.update-show', function(){
                let form = $('form#show-update'),
                    column = $('form#show-update input[name=column]'),
                    status = $('form#show-update input[name=status]');
                form.prop('action', $(this).data('action'))
                column.val($(this).data('column'))
                status.val($(this).prop('checked') ? 1 : 0)
                form.submit();
            });
            @endpermission
        })
    </script>
@endpush