@extends('layouts.dashboard.app', ['modals' => ['employee'], 'datatable' => true])

@section('title')
    اضافة مستخدم
@endsection


@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المستخدمين', 'إضافة'])
        @slot('url', [route('users.index'), '#'])
        @slot('icon', ['users', 'plus'])
    @endcomponent
    <form id="form" action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                      اضافة
                    </div>
                    <div class="box-header">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>@lang('users.username')</label>
                                  <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="@lang('users.username')" required>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label>الموظف</label>
                                <div class="input-group">
                                    <select class="form-control select2-single" name="employee_id">
                                      <option value="">الموظف</option>
                                      @foreach ($employees as $employee)
                                      @if (!isset($employee->user))
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                      @endif
                                      @endforeach
                                    </select>
                                    @permission('employees-create')
                                      <div class="input-group-btn">
                                        <button type="button" class="btn btn-success showEmployeeModal"><i class="fa fa-plus"></i></button>
                                      </div>
                                    @endpermission
					                      </div>
                              </div>
                          </div>
					</div>
					<div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('users.password')</label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="@lang('users.password')" required>
                            </div>
                          </div>
                          <div class="col-md-6">
							              <div class="form-group">
                                    <label>تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور" data-parsley-equalto="#password" required>
								            </div>
                          </div>
                        </div>
                    </div>
                    <div class="box-footer">
                      <div class="row">
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
                            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-plus"></i> إضافة</button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h3 class="box-title">الادوار و الصلاحيات</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
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
                            @foreach ($roles as $role)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                          {{ $role->name }}
                                          <input type="checkbox" class="flat-red" name="roles[]" value="{{ $role->id }}">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                      <div class="panel box box-danger">
                        <div class="box-header with-border">
                          <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                الصلاحيات
                            </a>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                          <div class="box-body">
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
                                @if( ( count($permissions) - ($index) ) > $missing_col  )
                                  <td>
                                      <label>
                                        {{ __('permissions.'.$permission->name)  }}
                                        <input type="checkbox" class="flat-red" name="permissions[]" value="{{ $permission->id }}">
                                      </label>
                                  </td>
                                @else
                                @if($missing_col == 1)
                                  @for ($i = 0; $i < $col; $i++)
                                      <td>
                                        <label>-</label>
                                      </td>
                                  @endfor
                                @endif
                                @if($missing_col > 0)
                                  <td>
                                    <label>
                                      {{ __('permissions.'.$permission->name)  }}
                                      <input type="checkbox" class="flat-red" name="permissions[]" value="{{ $permission->id }}">
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
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة</button>
                    </div>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </form>
@endsection
