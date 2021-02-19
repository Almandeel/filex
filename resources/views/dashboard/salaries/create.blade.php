@extends('layouts.dashboard.app', ['datatable' => true])

@section('title', 'الموظفين - ' . $employee->name . ' - المرتبات')

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموظفين',$employee->name, 'المرتبات'])
        @slot('url', [route('employees.index'), route('employees.show', $employee), '#'])
        @slot('icon', ['users', 'user', 'list'])
    @endcomponent
    
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">تفاصيل المعاملات المالية</h3>
            </div>
            <div class="box-body">
                <table id="transactions-table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>المعرف</th>
                            <th>الموظف</th>
                            <th>الخزنة</th>
                            <th>النوع</th>
                            <th>المبلغ</th>
                            <th>التفاصيل</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr class="transaction-row" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                            <td>{{ $transaction->id}}</td>
                            <td>{{ $transaction->user->employee->name}}</td>
                            <td>{{ $transaction->safe ? $transaction->safe->name : 'لاحقا'}}</td>
                            <td>{{ App\Transaction::TYPES[$transaction->type]}}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ $transaction->details}}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">تفاصيل المرتب</h3>
            </div>
            <div class="box-body">
                <form id="form_salaries" action="{{ route('salaries.store', $employee) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    السنة
                                </div>
                                <select class="form-control" name="year">
                                    @for($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}" @if($year==$i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    الشهر
                                </div>
                                <select class="form-control" name="month">
                                    @for($i = 1; $i <= 12; $i++) @php $m=$i < 10 ? '0' + $i : $i; @endphp <option value="{{ $m }}"
                                        @if($month==$m) selected @endif>{{ $m }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label>السلفيات</label>
                            <input class="form-control" readonly type="number" name="debts" value="{{ $totalDebts['total'] }}" placeholder="السلفيات">
                        </div>
                        <div class="col-md-4">
                            <label>الخصومات</label>
                            <input class="form-control" readonly type="number" name="deducations" value="{{ $totalDeducations['total'] }}" placeholder="الخصومات">
                        </div>
                        <div class="col-md-4">
                            <label>العلاوات</label>
                            <input class="form-control" readonly type="number" name="bonus" value="{{ $totalBonuses['total'] }}" placeholder="العلاوات">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>المرتب</label>
                            <input class="form-control" required autocomplete="off" type="number" name="total" value="{{$employee->salary}}" placeholder="المرتب">
                        </div>
                        <div class="col-md-6">
                            <label>الصافي</label>
                            <input class="form-control" required autocomplete="off" type="number" name="net" value="{{ $net }}" placeholder="الصافي">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-md-6">
                            <label>كلمة المرور الحالية</label>
                            <input type="password" class="form-control" name="password" placeholder="كلمة المرور الحالية" required>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <label>الخزنة</label>
                            <div class="input-group">
                                <select name="safe_id" class="form-control select2" required>
                                    @foreach (App\Safe::expensesSafes() as $s)
                                        @if ($s->balance() > 0)
                                            <option value="{{ $s->id }}">{{ $s->name }} (<strong>{{ $s->balance() }}</strong>)</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">صرف المرتب</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="employee_id" value="{{ $employee ->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('js')

<script>
    $(function () {
        {{--  $('.transaction-row').click((e)=>{
            $(this).tooltip('show');
        });  --}}
    })
</script>
@endpush
