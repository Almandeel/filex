@extends('layouts.dashboard.app', ['modals' => ['transfer', 'expenses'], 'datatable' => true])

@section('title')
    خزنة : {{ $safe->name }}
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الخزن',  $safe->name])
        @slot('url', [route('safes.index'), '#'])
        @slot('icon', ['money', ''])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            <table class="datatable table table-bordered">
                <thead>
                    <tr>
                        <th>الخزنة</th>
                        <th>الرصيد</th>
                        <th>الرصيد الافتتاحي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $safe->name }}</td>
                        <td>{{ number_format($safe->account->balance(), 2) }}</td>
                        <td>
                            @if (count($safe->account->openingEntries()))
                                {{ number_format($safe->account->openingEntries()->last()->amount, 2) }}
                                {{-- {{ str_replace(".00", "", (string) number_format($safe->account->openingEntries()->last()->amount, 2)) }} --}}
                            @else
                                @permission('transfers-create')
                                <form action="{{ route('transfers.store') }}" method="POST" class="form-inline">
                                    @csrf
                                    <label for="amount">رصيد افتتاحي</label>
                                    <input type="number" name="amount" id="amount" class="form-control"/> {{-- max="{{ App\Account::capital()->balance() }}" required /> --}}
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-plus"></i>
                                        <span>اضافة</span>
                                    </button>
                                    <input type="hidden" name="from_id" value="{{ $safe->account->id }}">
                                    {{-- <input type="hidden" name="to_id" value="{{ App\Account::capital()->id }}"> --}}
                                    {{--  <input type="hidden" name="max" value="{{ App\Account::capital()->balance() }}">  --}}
                                    <input type="hidden" name="type" value="{{ App\Entry::TYPE_OPEN }}">
                                    <input type="hidden" name="details" value="{{ 'عبارة عن رصيد افتاحي للخزنة رقم: ' . $safe->id }}">
                                </form>
                                @endpermission
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="{{ $activeTab == 'payments' ? 'active' : '' }}"><a href="#tab-payments" data-toggle="tab" aria-expanded="true">
                <span>الدفعات</span>
            </a></li>
            <li class="{{ $activeTab == 'expenses' ? 'active' : '' }}"><a href="#tab-expenses" data-toggle="tab" aria-expanded="true">
                <span>المنصرفات</span>
            </a></li>
            <li class="{{ $activeTab == 'transfers' ? 'active' : '' }}"><a href="#tab-transfers" data-toggle="tab" aria-expanded="true">
                <span>التحويلات</span>
            </a></li>
            <li class="{{ $activeTab == 'cheques' ? 'active' : '' }}"><a href="#tab-cheques" data-toggle="tab" aria-expanded="true">
                <span>الشيكات</span>
            </a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <span>
                        <span>إضافة</span>
                        (<span>تحويل</span>
                        <span>/</span>
                        <span>منصرف</span>)
                    </span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @permission('transfers-create')
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"
                            class="showTransferModal"
                            data-to="{{ $safe->id }}"
                            data-max="{{ $safe->account->balance() }}"
                        >
                            <i class="fa fa-plus"></i>
                            <span>تحويل</span>
                        </a></li>
                    @endpermission
                    @permission('expenses-create')
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"
                            class="showexpensesModal"
                            data-safe-id="{{ $safe->id }}"
                            data-max="{{ $safe->account->balance() }}"
                        >
                            <i class="fa fa-plus"></i>
                            <span>منصرف</span>
                        </a></li>
                    @endpermission
                </ul>
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ $activeTab == 'payments' ? 'active' : '' }}" id="tab-payments">
                <legend>
                    <a href="#payments-search" data-toggle="collapse">
                        <i class="fa fa-cogs"></i>
                        <span>بحث متقدم</span>
                    </a>
                </legend>
                <div id="payments-search" class="collapse well">
                    <form action="" method="GET" class="form-inline d-inline-block">
                        @csrf
                        <label for="payments-from-date">من</label>
                        <input type="date" name="from_date" id="payments-from-date" value="{{ $from_date }}" class="form-control">
                        <label for="payments-to-date">الى</label>
                        <input type="date" name="to_date" id="payments-to-date" value="{{ $to_date }}" class="form-control">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                            <span></span>
                        </button>
                        <input type="hidden" name="account_id" value="{{ $safe->account->id }}">
                    </form>
                </div>
                <table id="payments-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>القيمة</th>
                            <th>التفاصيل</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            {{--  <th>الخيارات</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $index=>$payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->details }}</td>
                            {{--  <td>{{ str_limit($payment->details, 30) }}</td>  --}}
                            <td>{{ $payment->user->employee->name }}</td>
                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                            {{--  <td>
                                @permission('payments-read')
                                <a class="btn btn-info btn-xs" href="{{ route('payments.show', $payment->id) }}"><i
                                        class="fa fa-eye"></i> عرض </a>
                                @endpermission
            
                                @permission('payments-update')
                                <a class="btn btn-warning btn-xs showpaymentsModal  update"
                                    data-action="{{ route('payments.update', $payment->id) }}" data-amount="{{ $payment->amount }}"
                                    data-safe-id="{{ $safe->id }}" data-max="{{ $safe->account->balance() + $payment->amount }}"
                                    data-details="{{ $payment->details }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission
            
                                @permission('payments-delete')
                                <form style="display:inline-block" action="{{ route('payments.destroy', $payment->id) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                </form>
                                @endpermission
                            </td>  --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane {{ $activeTab == 'expenses' ? 'active' : '' }}" id="tab-expenses">
                <legend>
                    <a href="#expenses-search" data-toggle="collapse">
                        <i class="fa fa-cogs"></i>
                        <span>بحث متقدم</span>
                    </a>
                </legend>
                <div id="expenses-search" class="collapse well">
                    <form action="" method="GET" class="form-inline d-inline-block">
                        @csrf
                        <label for="expenses-from-date">من</label>
                        <input type="date" name="from_date" id="expenses-from-date" value="{{ $from_date }}" class="form-control">
                        <label for="expenses-to-date">الى</label>
                        <input type="date" name="to_date" id="expenses-to-date" value="{{ $to_date }}" class="form-control">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                            <span></span>
                        </button>
                        <input type="hidden" name="account_id" value="{{ $safe->account->id }}">
                    </form>
                </div>
                <table id="expenses-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>القيمة</th>
                            <th>التفاصيل</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $index=>$expense)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $expense->amount }}</td>
                            <td>{{ str_limit($expense->details, 30) }}</td>
                            <td>{{ $expense->user->employee->name }}</td>
                            <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                            <td>
                                @permission('expenses-read')
                                <a class="btn btn-info btn-xs" href="{{ route('expenses.show', $expense->id) }}"><i
                                        class="fa fa-eye"></i> عرض </a>
                                @endpermission
                
                                @permission('expenses-update')
                                <a class="btn btn-warning btn-xs showexpensesModal  update"
                                    data-action="{{ route('expenses.update', $expense->id) }}" 
                                    data-amount="{{ $expense->amount }}"
                                    data-safe-id="{{ $safe->id }}"
                                    data-max="{{ $safe->account->balance() + $expense->amount }}"
                                    data-details="{{ $expense->details }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission
                
                                @permission('expenses-delete')
                                <form style="display:inline-block" action="{{ route('expenses.destroy', $expense->id) }}" method="post">
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
            </div>
            <div class="tab-pane {{ $activeTab == 'transfers' ? 'active' : '' }}" id="tab-transfers">
                <div>
                    <legend>
                        <a href="#transfers-search" data-toggle="collapse" clas="btn btn-secondary btn-sm mb-10 d-inline-block">
                            <i class="fa fa-cogs"></i>
                            <span>بحث متقدم</span>
                        </a>
                    </legend>
                    <div id="transfers-search" class="collapse well">
                        <form action="" method="GET" class="form-inline d-inline-block">
                            @csrf
                            <label for="transfers-from-date">من</label>
                            <input type="date" name="from_date" id="transfers-from-date" value="{{ $from_date }}" class="form-control">
                            <label for="transfers-to-date">الى</label>
                            <input type="date" name="to_date" id="transfers-to-date" value="{{ $to_date }}" class="form-control">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                <span></span>
                            </button>
                            <input type="hidden" name="account_id" value="{{ $safe->account->id }}">
                        </form>
                    </div>
                </div>
                <table id="transfers-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الخزنة</th>
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
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                @if ($transfer->from_id == $safe->id)
                                    {{ $transfer->to ? $transfer->to->name : $transfer->toAccount->getName() }}
                                @else
                                    {{ $transfer->from ? $transfer->from->name : $transfer->fromAccount->getName() }}                                    
                                @endif
                            </td>
                            <td>
                                @if($transfer->from_id === $safe->account->id)
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
                                @if ($transfer->to_id === $safe->account->id)
                                    @permission('transfers-update')
                                    <a class="btn btn-warning btn-xs showTransferModal  update"
                                        data-action="{{ route('transfers.update', $transfer) }}" data-amount="{{ $transfer->amount }}"
                                        data-method="PUT"
                                        data-details="{{ $transfer->details }}" 
                                        data-current="{{ $safe->id}}"
                                        data-from="{{ $transfer->from_id}}"
                                        data-to="{{ $transfer->to_id }}" 
                                        data-max="{{ $transfer->to_id == $safe->id ? $safe->account->balance() + $transfer->amount : $transfer->to->account->balance() + $transfer->amount }}">
                                        <i class="fa fa-edit"></i>
                                        <span>تعديل</span>
                                    </a>
                                    @endpermission
                    
                                    @permission('transfers-delete')
                                    <form style="display:inline-block" action="{{ route('transfers.destroy', $transfer->id) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                    </form>
                                    @endpermission
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane {{ $activeTab == 'cheques' ? 'active' : '' }}" id="tab-cheques">
                <legend>
                    <a href="#cheques-search" data-toggle="collapse">
                        <i class="fa fa-cogs"></i>
                        <span>بحث متقدم</span>
                    </a>
                </legend>
                <div id="cheques-search" class="collapse well">
                    <form action="" method="GET" class="form-inline d-inline-block">
                        @csrf
                        <label for="cheques-from-date">من</label>
                        <input type="date" name="from_date" id="cheques-from-date" value="{{ $from_date }}" class="form-control">
                        <label for="cheques-to-date">الى</label>
                        <input type="date" name="to_date" id="cheques-to-date" value="{{ $to_date }}" class="form-control">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                            <span></span>
                        </button>
                        <input type="hidden" name="account_id" value="{{ $safe->account->id }}">
                    </form>
                </div>
                <table id="cheques-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>البنك</th>
                            <th>القيمة</th>
                            <th>المستفيد</th>
                            <th>تاريخ الإستحقاق</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalAmount = 0;
                        @endphp
                        @foreach ($cheques as $cheque)
                        <tr>
                            <td>{{ $cheque->number }}</td>
                            <td>{{ $cheque->getType() }}</td>
                            <td>{{ $cheque->getStatus() }}</td>
                            <td>{{ $cheque->bank_name }}</td>
                            <td>{{ $cheque->amount }}</td>
                            <td>{{ $cheque->getBenefit() }}</td>
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
                                    data-action="{{ route('cheques.update', $cheque->id) }}" data-id="{{ $cheque->id }}"
                                    data-bank="{{ $cheque->bank_name }}" data-number="{{ $cheque->number }}" data-type="{{ $cheque->type }}"
                                    data-benefit="{{ str_replace('App\\', '', $cheque->benefit) }}"
                                    data-benefit_id="{{ $cheque->benefit_id }}" data-amount="{{ $cheque->amount }}"
                                    data-due_date="{{ $cheque->due_date }}" data-details="{{ $cheque->details }}"><i class="fa fa-edit"></i>
                                    تعديل </a>
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
                        @php
                        $totalAmount += $cheque->amount;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="totalAmount">{{ $totalAmount }}</th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="active-tab" name="active_tab" value="{{ $activeTab }}" />
@endsection
@push('js')
    <script>
        $(function(){
            $('form').on('submit', (e) => {
                $(e.target).append($('input#active-tab'))
            })
            $('.nav.nav-tabs > li > a').click(function(){
                if(!$(this).parent().hasClass('dropdown')){
                    let tab = $(this).attr('href') + "";
                    $('input#active-tab').val(tab.substring(5, tab.length))
                }
            })
        })
    </script>
@endpush
