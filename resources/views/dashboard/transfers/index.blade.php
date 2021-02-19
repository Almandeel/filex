@extends('layouts.dashboard.app', ['modals' => ['transfer'], 'datatable' => true])

@section('title')
    المنصرفات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['خزنة: ' . $account->getName()])
        @slot('url', ['#'])
        @slot('icon', ['dollar'])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الخزنة</th>
                        <th>الرصيد</th>
                        <th>الرصيد الافتتاحي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $account->getName() }}</td>
                        <td>{{ number_format($account->balance(), 2) }}</td>
                        <td>
                            @if (count($account->openingEntries()))
                                {{ number_format($account->openingEntries()->last()->amount, 2) }}
                                {{-- {{ str_replace(".00", "", (string) number_format($account->openingEntries()->last()->amount, 2)) }} --}}
                            @else
                                @permission('transfers-create')
                                <form action="{{ route('transfers.store') }}" method="POST" class="form-inline">
                                    @csrf
                                    <label for="amount">رصيد افتتاحي</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-plus"></i>
                                        <span>اضافة</span>
                                    </button>
                                    <input type="hidden" name="account_id" value="{{ $account->id }}">
                                </form>
                                @endpermission
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box">
        <div class="box-header">
            <h4 class="box-title">
                التحويلات
            </h4>
            &nbsp;&nbsp;&nbsp;
            <form action="{{ route('transfers.index') }}" method="GET" class="form-inline d-inline">
                @csrf
                <label for="from_date">من</label>
                <input type="date" name="from_date" id="from_date" value="{{ $from_date }}" class="form-control">
                <label for="to_date">الى</label>
                <input type="date" name="to_date" id="to_date" value="{{ $to_date }}" class="form-control">
                <button type="submit" class="btn btn-secondary">
                    <i class="fa fa-search"></i>
                    <span></span>
                </button>
                <input type="hidden" name="account_id" value="{{ $account->id }}">
            </form>
            <div class="box-tools">
                @permission('transfers-create')
                <button type="button" style="display:inline-block; margin-left:1%"
                    class="btn btn-primary btn-sm pull-right showTransferModal create" data-toggle="modal" data-target="#transfers"
                    data-from="{{ $account->id == \App\Account::safe()->id ? \App\Account::bank()->id : \App\Account::safe()->id }}"
                    data-to="{{ $account->id }}"
                    data-max="{{ $account->balance() }}"
                    >
                    <i class="fa fa-plus"></i>
                    <span>تحويل</span>
                </button>
                @endpermission
            </div>
        </div>
        <div class="box-body">
            <table id="transfers-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>العملية</th>
                        <th>القيمة</th>
                        <th>التفاصيل</th>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $transfer)
                        <tr>
                            <td>{{ $transfer->id }}</td>
                            <td>
                                @if($transfer->from_id === $account->id)
                                    ايداع
                                @else
                                    سحب
                                @endif
                            </td>
                            <td>{{ $transfer->amount }}</td>
                            <td>{{ str_limit($transfer->details, 30) }}</td>
                            <td>{{ $transfer->user->employee->name }}</td>
                            <td>{{ $transfer->created_at->format('Y-m-d') }}</td>
                            <td>
                                @permission('transfers-update')
                                    <a class="btn btn-warning btn-xs showTransferModal  update" data-action="{{ route('transfers.update', $transfer) }}" 
                                        data-amount="{{ $transfer->amount }}" data-details="{{ $transfer->details }}"
                                        data-from="{{ $transfer->from_id}}"
                                        data-to="{{ $transfer->to_id }}"
                                        data-max="{{ $account->balance() + $transfer->amount }}">
                                        <i class="fa fa-edit"></i>
                                        <span>تعديل</span> 
                                    </a>
                                @endpermission

                                @permission('transfers-delete')
                                <form style="display:inline-block" action="{{ route('transfers.destroy', $transfer->id) }}" method="post">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('transfers.store') }}" id="create">
        </div>
    </div>
@endsection
