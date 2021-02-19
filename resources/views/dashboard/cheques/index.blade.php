@extends('layouts.dashboard.app', ['modals' => ['cheque'], 'datatable' => true])

@section('title')
    الشيكات المصرفية
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الشيكات المصرفية'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            @permission('cheques-create')
                <button type="button" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right showChequeModal create"  data-toggle="modal" data-target="#cheques">
                    <i class="fa fa-plus">إضافة</i>
                </button>
            @endpermission
        </div>
        <div class="box-body">
            <legend>
                <a href="#cheques-search" data-toggle="collapse">
                    <i class="fa fa-cogs"></i>
                    <span>بحث متقدم</span>
                </a>
            </legend>
            <div id="cheques-search" class="collapse well {{ $advanced_search ? 'in' : '' }}">
                <form action="" method="GET" class="form-inline d-inline-block">
                    @csrf
                    <label for="cheques-from-type">النوع</label>
                    <select name="type" id="cheques-from-type" class="form-control">
                        <option value="111001" {{ $type == 111001 ? 'selected' : '' }}>الكل</option>
                        @foreach (App\Cheque::TYPES as $chequeType => $name)
                        <option value="{{ $chequeType }}" {{ $chequeType == $type ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <label for="cheques-from-status">الحالة</label>
                    <select name="status" id="cheques-from-status" class="form-control">
                        <option value="111001" {{ $status == 111001 ? 'selected' : '' }}>الكل</option>
                        @foreach (App\Cheque::STATUSES as $chequeStatus => $name)
                        <option value="{{ $chequeStatus }}" {{ $chequeStatus == $status ? 'selected' : '' }}>{{ $name }}
                        </option>
                        @endforeach
                    </select>
                    <label>
                        <input type="checkbox" name="using_dates" {{ $using_dates ? 'checked' : '' }}>
                        <span>بين تاريخين:</span>
                    </label>
                    <label for="cheques-from-date">من</label>
                    <input type="date" name="from_date" id="cheques-from-date" value="{{ $from_date }}" class="form-control">
                    <label for="cheques-to-date">الى</label>
                    <input type="date" name="to_date" id="cheques-to-date" value="{{ $to_date }}" class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                        <span></span>
                    </button>
                </form>
            </div>
            <div class="table-wrapper">
                <table id="cheques-table" class="table datatable table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الخزنة</th>
                            <th>الرقم</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>البنك</th>
                            <th>القيمة</th>
                            <th>تاريخ الإستحقاق</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cheques as $cheque)
                        <tr>
                            <td>
                                @if (auth()->user()->can('safes-read'))
                                    <a href="{{ route('safes.show', $cheque->safe) }}">{{ $cheque->safe->name }}</a>
                                @else
                                    {{ $cheque->safe->name }}
                                @endif
                            </td>
                            <td>{{ $cheque->number }}</td>
                            <td>{{ $cheque->getType() }}</td>
                            <td>{{ $cheque->getStatus() }}</td>
                            <td>{{ $cheque->bank_name }}</td>
                            <td>{{ $cheque->amount }}</td>
                            <td>{{ $cheque->due_date }}</td>
                            <td>
                                @if($cheque->status == App\Cheque::STATUS_WAITING)
                                    @permission('cheques-update')
                                        <form action="{{ route('cheques.update', $cheque) }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-xs">
                                                <i class="fa fa-check"></i>
                                                <span>تأكيد الدفع</span>
                                            </button>
                                            <input type="hidden" name="status" value="{{ App\Cheque::STATUS_DELEVERED }}">
                                        </form>
                                    @endpermission
                                @permission('cheques-update')
                                <a class="btn btn-warning btn-xs showChequeModal update"
                                    data-action="{{ route('cheques.update', $cheque->id) }}"
                                    data-id="{{ $cheque->id }}"
                                    data-bank="{{ $cheque->bank_name }}" 
                                    data-number="{{ $cheque->number }}"
                                    data-type="{{ $cheque->type }}" 
                                    data-benefit="{{ str_replace('App\\', '', $cheque->benefit) }}"
                                    data-benefit_id="{{ $cheque->benefit_id }}" 
                                    data-amount="{{ $cheque->amount }}"
                                    data-due_date="{{ $cheque->due_date }}" 
                                    data-details="{{ $cheque->details }}"><i
                                    class="fa fa-edit"></i> تعديل </a>
                                @endpermission
                
                                @permission('cheques-update')
                                <form action="{{ route('cheques.update', $cheque) }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-xs">
                                        <i class="fa fa-ban"></i>
                                        <span>إلغاء</span>
                                    </button>
                                    <input type="hidden" name="status" value="{{ App\Cheque::STATUS_CANCELED }}">
                                </form>                                 
                                @endpermission
                                @endif
                
                                @if($cheque->status == App\Cheque::STATUS_WAITING)
                                    @permission('cheques-delete')
                                        <form action="{{ route('cheques.destroy', $cheque) }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs delete">
                                                <i class="fa fa-times"></i>
                                                <span>حذف</span>
                                            </button>
                                        </form>
                                    @endpermission
                                @endif
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="totalAmount">{{ $totalAmount }}</th>
                        <th></th>
                        <th></th>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function(){
            $('input[name=from_date], input[name=to_date]').change(function(){
                $('input[name=using_dates]').prop('checked', true)
            })
        })
    </script>
@endpush