@extends('layouts.dashboard.app', [
    'datatable' => true
])

@section('title', 'الموظفين - ' . 'إضافة')

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموظفين','إضافة'])
        @slot('url', [route('employees.index'), '#'])
        @slot('icon', ['users', ''])
    @endcomponent
    <form class="form" action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="form-group row">
            <div class="col-xs-12 col-md-6">
                <label>الاسم</label>
                <input class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required />
            </div>
            <div class="col-xs-12 col-md-6">
                <label>المرتب</label>
                <input class="form-control" autocomplete="off" type="number" name="salary" placeholder="المرتب" required />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-xs-12 col-md-6">
                <label>رقم الهاتف</label>
                <input class="form-control" autocomplete="off" type="text" name="phone" placeholder="رقم الهاتف" required />
            </div>
            <div class="col-xs-12 col-md-6">
                <label> العنوان</label>
                <input class="form-control" autocomplete="off" type="text" name="address" placeholder="رقم العنوان" required />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label>
                    <input type="radio" name="next" value="back" checked="true">
                    <span>حفظ و اضافة جديد</span>
                </label>
                <label>
                    <input type="radio" name="next" value="list">
                    <span>حفظ و عرض على القائمة</span>
                </label>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-check"></i> إكمال العملية</button>
            </div>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="with_user">
                <span>إنشاء بيانات دخول</span>
            </label>
        </div>
        <fieldset id="user-details" style="display: none;">
            <legend>بيانات الدخول</legend>
            <div class="form-group">
                <div class="form-group">
                    <label>@lang('users.username')</label>
                    <input type="text" class="form-control required" name="username" value="{{ old('username') }}"
                        placeholder="@lang('users.username')" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('users.password')</label>
                        <input id="password" type="password" class="form-control required" name="password"
                            placeholder="@lang('users.password')" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>تأكيد كلمة المرور</label>
                        <input type="password" class="form-control required" name="password_confirmation" placeholder="تأكيد كلمة المرور" data-parsley-equalto="#password" required>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label>
                        <input type="radio" name="next" value="back">
                        <span>حفظ و اضافة جديد</span>
                    </label>
                    <label>
                        <input type="radio" name="next" value="list">
                        <span>حفظ و عرض على القائمة</span>
                    </label>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-check"></i> إكمال العملية</button>
                </div>
            </div>
            <div class="form-group">
                <div class="box-group" id="accordion">
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    الادوار
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="box-body text-left">
                                <div class="row">
                                    @foreach ($roles as $role)
                                    <div class="col-xs-6 col-sm-4 col-md-3 text-right">
                                        <label>
                                            <input type="checkbox" class="flat-red" name="roles[]" value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 text-right">
                                        <label>
                                            <input type="radio" name="next" value="back">
                                            <span>حفظ و اضافة جديد</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="next" value="list">
                                            <span>حفظ و عرض على القائمة</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-check"></i> إكمال العملية</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    الصلاحيات
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="form-group">
                                <table id="users-table" class="table table-bordered table-hover text-right" dir="ltr">
                                    <thead>
                                        <tr>
                                            <th>الصلاحية </th>
                                            <th>الصلاحية</th>
                                            <th>الصلاحية</th>
                                            <th>الصلاحية</th>
                                        </tr>
                                    </thead>
                                    @php $missing_col = count($permissions) % 4 @endphp
                                    @php $col = 4 - $missing_col @endphp
                                    @foreach ($permissions as $index=>$permission)
                                    @if($index % 4 == 0 )
                                    <tr>
                                        @endif
                                        @if( ( count($permissions) - ($index) ) > $missing_col )
                                        <td>
                                            <label>
                                                {{ __('permissions.'.$permission->name)  }}
                                                <input type="checkbox" class="flat-red" name="permissions[]"
                                                    value="{{ $permission->id }}">
                                            </label>
                                        </td>
                                        @else
                                        @if($missing_col == 1)
                                        @for ($i = 0; $i < $col; $i++) <td>
                                            <label>-</label>
                                            </td>
                                            @endfor
                                            @endif
                                            @if($missing_col > 0)
                                            <td>
                                                <label>
                                                    {{ __('permissions.'.$permission->name)  }}
                                                    <input type="checkbox" class="flat-red" name="permissions[]"
                                                        value="{{ $permission->id }}">
                                                </label>
                                            </td>
                                            @php $missing_col-- @endphp
                                            @endif
        
                                            @endif
                                            @if($index + 1 % 4 == 0)
                                    </tr>
                                    @endif
                                    @endforeach
                                </table>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>
                                        <input type="radio" name="next" value="back">
                                        <span>حفظ و اضافة جديد</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="next" value="list">
                                        <span>حفظ و عرض على القائمة</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-check"></i> إكمال العملية</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
@endsection
@push('js')
    <script>
        $(function(){
            $('input[name=with_user]').change(function(){
                if($(this).prop('checked')){
                    $(this).siblings('span').text('إخفاء بيانات دخول')
                    $('#user-details').fadeIn();
                    $('#user-details .required').prop('required', true);
                }else{
                    $(this).siblings('span').text('إنشاء بيانات دخول')
                    $('#user-details').fadeOut();
                    $('#user-details .required').removeAttr('required');
                }
            })
        })
    </script>
@endpush