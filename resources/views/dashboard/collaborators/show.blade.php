@extends('layouts.dashboard.app', ['modals' => ['transfer', 'collaborator', 'chequeInput'], 'datatable' => true])
@section('title')
    المتعاونون : {{ $collaborator->name }}
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المتعاونون', $collaborator->name])
        @slot('url', [route('collaborators.index'), '#'])
        @slot('icon', ['users', 'user'])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            <table class="table table-borderd">
                <tr>
                    <th>الاسم</th>
                    <td>{{ $collaborator->name }}</td>
                </tr>
                <tr>
                    <th>الهاتف</th>
                    <td>{{ $collaborator->phone }}</td>
                </tr>
                <tr>
                    <th>الرصيد</th>
                    <td>{{ number_format(abs($collaborator->balance())) }} <strong class="badge {{ ($collaborator->balance()) < 0 ? 'badge-warning' : (($collaborator->balance() > 0) ? 'badge-success' : '')}}">{{ $collaborator->balanceType() }}</strong></td>
                </tr>
            </table>
        </div>
        <div class="box-footer">
            @if(request()->delete)
                @permission('collaborators-delete')
                    <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                @endpermission
            @else
                @permission('collaborators-delete')
                    <a class="btn btn-warning btn-xs showCollaboratorModal update" data-action="{{ route('collaborators.update', $collaborator->id) }}" data-id="{{ $collaborator->id }}" data-name="{{ $collaborator->name }}" data-phone="{{ $collaborator->phone }}" data-address="{{ $collaborator->address }}"><i class="fa fa-edit"></i> تعديل </a>
                @endpermission
            @endif
        </div>
    </div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="{{ $activeTab == 'debts' ? 'active' : '' }}"><a href="#tab-debts" data-toggle="tab" aria-expanded="true">
                <span>المسحوبات</span>
            </a></li>
            <li class="{{ $activeTab == 'credits' ? 'active' : '' }}"><a href="#tab-credits" data-toggle="tab" aria-expanded="true">
                <span>الودائع</span>
            </a></li>
            <li class="{{ $activeTab == 'cheques' ? 'active' : '' }}"><a href="#tab-cheques" data-toggle="tab" aria-expanded="true">
                <span>الشيكات</span>
            </a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <span>
                        <span>إضافة</span>
                        (<span>سحب</span>
                        <span>/</span>
                        <span>ايداع</span>
                        <span>/</span>
                        <span>شيك</span>)
                    </span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @permission('entries-create')
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"
                            class="showTransferModal"
                            data-from="{{ $collaborator->id }}"
                            data-title="سحب"
                            data-action="{{ route('entries.store') }}"
                            data-collaborate="debt"
                            data-collaborator-id="{{ $collaborator->id }}"
                        >
                            <i class="fa fa-plus"></i>
                            <span>سحب</span>
                        </a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"
                            class="showTransferModal"
                            data-title="ايداع"
                            data-action="{{ route('entries.store') }}"
                            data-to="{{ $collaborator->id }}"
                            data-collaborate="credit"
                            data-collaborator-id="{{ $collaborator->id }}"
                        >
                            <i class="fa fa-plus"></i>
                            <span>ايداع</span>
                        </a></li>
                    @endpermission
                    @permission('cheques-create')
						<li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" 
                            class="showChequeInputModal" data-show-type="true" data-form="ChequeForm">
							<i class="fa fa-plus"></i>
							<span>شيك</span>
						</a></li>
                    @endpermission
                </ul>
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ $activeTab == 'debts' ? 'active' : '' }}" id="tab-debts">
                <div>
                    <legend>
                        <a href="#debts-search" data-toggle="collapse" clas="btn btn-secondary btn-sm mb-10 d-inline-block">
                            <i class="fa fa-cogs"></i>
                            <span>بحث متقدم</span>
                        </a>
                    </legend>
                    <div id="debts-search" class="collapse well">
                        <form action="" method="GET" class="form-inline d-inline-block">
                            @csrf
                            <label for="debts-from-date">من</label>
                            <input type="date" name="from_date" id="debts-from-date" value="{{ $from_date }}" class="form-control">
                            <label for="debts-to-date">الى</label>
                            <input type="date" name="to_date" id="debts-to-date" value="{{ $to_date }}" class="form-control">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                <span></span>
                            </button>
                            <input type="hidden" name="account_id" value="{{ $collaborator->account->id }}">
                        </form>
                    </div>
                </div>
                <table id="debts-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>الخزنة</th>
                            <th>القيمة</th>
                            <th>التفاصيل</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($debts as $debt)
                        <tr>
                            <td>{{ $debt->id }}</td>
                            <td>{{ $debt->to->name }}</td>
                            <td>{{ $debt->amount }}</td>
                            <td>{{ str_limit($debt->details, 30) }}</td>
                            <td>{{ $debt->user->employee->name }}</td>
                            <td>{{ $debt->created_at->format('Y-m-d') }}</td>
                            <td>
                                @permission('entries-update')
                                <a class="btn btn-warning btn-xs showTransferModal  update"
                                    data-title="تعديل سحب"
                                    data-action="{{ route('entries.update', $debt) }}" 
                                    data-amount="{{ $debt->amount }}"
                                    data-method="PUT"
                                    data-details="{{ $debt->details }}" 
                                    data-collaborate="debt"
                                    data-collaborator-id="{{ $collaborator->id }}"
                                    data-safe-id="{{ $debt->to_id }}" 
                                    <i class="fa fa-edit"></i>
                                    <span>تعديل</span>
                                </a>
                                @endpermission
                
                                @permission('entries-delete')
                                <form style="display:inline-block" action="{{ route('entries.destroy', $debt->id) }}"
                                    method="post">
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
            <div class="tab-pane {{ $activeTab == 'credits' ? 'active' : '' }}" id="tab-credits">
                <div>
                    <legend>
                        <a href="#credits-search" data-toggle="collapse" clas="btn btn-secondary btn-sm mb-10 d-inline-block">
                            <i class="fa fa-cogs"></i>
                            <span>بحث متقدم</span>
                        </a>
                    </legend>
                    <div id="credits-search" class="collapse well">
                        <form action="" method="GET" class="form-inline d-inline-block">
                            @csrf
                            <label for="credits-from-date">من</label>
                            <input type="date" name="from_date" id="credits-from-date" value="{{ $from_date }}" class="form-control">
                            <label for="credits-to-date">الى</label>
                            <input type="date" name="to_date" id="credits-to-date" value="{{ $to_date }}" class="form-control">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                <span></span>
                            </button>
                            <input type="hidden" name="account_id" value="{{ $collaborator->account->id }}">
                        </form>
                    </div>
                </div>
                <table id="credits-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>الخزنة</th>
                            <th>القيمة</th>
                            <th>التفاصيل</th>
                            <th>الموظف</th>
                            <th>التاريخ</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($credits as $credit)
                        <tr>
                            <td>{{ $credit->id }}</td>
                            <td>{{ $credit->from->name }}</td>
                            <td>{{ $credit->amount }}</td>
                            <td>{{ str_limit($credit->details, 30) }}</td>
                            <td>{{ $credit->user->employee->name }}</td>
                            <td>{{ $credit->created_at->format('Y-m-d') }}</td>
                            <td>
                                @permission('entries-update')
                                <a class="btn btn-warning btn-xs showTransferModal  update"
                                    data-title="تعديل ايداع"
                                    data-collaborate="credit"
                                    data-collaborator-id="{{ $collaborator->id }}"
                                    data-action="{{ route('entries.update', $credit) }}" 
                                    data-amount="{{ $credit->amount }}"
                                    data-method="PUT"
                                    data-details="{{ $credit->details }}" 
                                    data-safe-id="{{ $credit->from_id}}"
                                    <i class="fa fa-edit"></i>
                                    <span>تعديل</span>
                                </a>
                                @endpermission
                
                                @permission('entries-delete')
                                <form style="display:inline-block" action="{{ route('entries.destroy', $credit->id) }}"
                                    method="post">
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
            <div class="tab-pane {{ $activeTab == 'cheques' ? 'active' : '' }}" id="tab-cheques">
                <legend>
                    <a href="#cheques-search" data-toggle="collapse">
                        <i class="fa fa-cogs"></i>
                        <span>بحث متقدم</span>
                    </a>
                </legend>
                <div id="cheques-search" class="collapse well {{ $cheques_advanced_search ? 'in' : '' }}">
                    <form action="" method="GET" class="form-inline d-inline-block">
                        @csrf
                        <label for="cheques-from-type">النوع</label>
                        <select name="cheques_type" id="cheques-from-type" class="form-control">
                            <option value="111001" {{ $cheques_type == 111001 ? 'selected' : '' }}>الكل</option>
                            @foreach (App\Cheque::TYPES as $chequeType => $name)
                                <option value="{{ $chequeType }}" {{ $chequeType == $cheques_type ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <label for="cheques-from-status">الحالة</label>
                        <select name="cheques_status" id="cheques-from-status" class="form-control">
                            <option value="111001" {{ $cheques_status == 111001 ? 'selected' : '' }}>الكل</option>
                            @foreach (App\Cheque::STATUSES as $chequeStatus => $name)
                                <option value="{{ $chequeStatus }}" {{ $chequeStatus == $cheques_status ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <label>
                            <input type="checkbox" name="cheques_using_dates" {{ $cheques_using_dates ? 'checked' : '' }}>
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
                <table id="cheques-table" class="datatable table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>البنك</th>
                            <th>القيمة</th>
                            <th>الخزنة</th>
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
                            <td>{{ $cheque->safe->name }}</td>
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
                                <a href="#" class="btn btn-warning btn-xs showChequeInputModal" 
                                    data-show-type="true" 
                                    data-form="ChequeForm"
                                    data-label="تعديل شيك" 
                                    data-action="{{ route('cheques.update', $cheque) }}" 
                                    data-method="PUT"
                                    data-type="{{ $cheque->type }}"
                                    data-number="{{ $cheque->number }}" data-bank="{{ $cheque->bank_name }}"
                                    data-account-id="{{ $cheque->account_id }}" data-amount="{{ $cheque->amount }}"
                                    data-due-date="{{ $cheque->due_date }}"><i class="fa fa-edit"></i> تعديل </a>
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

    {{--  ChequeForm  --}}
    <form id="ChequeForm" action="{{ route('cheques.store') }}" method="post">
        @csrf
        <input type="hidden" name="amount" value="0" />
        <input type="hidden" name="number">
        <input type="hidden" name="bank_name">
        <input type="hidden" name="due_date">
        <input type="hidden" name="account_id">
        <input type="hidden" name="type">
        <input type="hidden" name='details'>

        <input type="hidden" name='benefit' value="{{ App\Cheque::BENEFIT_COLLABORATOR }}" />
        <input type="hidden" name='benefit_id' value="{{ $collaborator->id }}" />  
    </form>
    <input type="hidden" id="active-tab" name="active_tab" value="{{ $activeTab }}" />
@endsection
@push('js')
    <script>
        $(function(){
            $('form').on('submit', (e) => {
                $(e.target).append($('input#active-tab'))
            })
            $('.nav.nav-tabs > li > a').click(function(){
                let tab = $(this).attr('href') + "";
                $('input#active-tab').val(tab.substring(5, tab.length))
            })
            $('input[name=from_date], input[name=to_date]').change(function(){
                $('input[name=using_dates]').prop('checked', true)
            })
        })
    </script>
@endpush