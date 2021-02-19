@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
  تعديل
@endsection


@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المستخدمين', $user->username ,'تعديل'])
        @slot('url', [route('users.index'), route('users.show', $user->id), '#'])
        @slot('icon', ['users', 'user', 'edit'])
    @endcomponent
<form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        {{ method_field('PUT') }}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        تعديل
                    </div>
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('users.username')</label>
                                <input type="text" class="form-control" name="username" value="{{ $user->username }}" placeholder="@lang('users.username')" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>الموظف</label>
                              <div class="input-group">
                                  <select class="form-control select2-single" name="employee_id">
                                    <option value="">الموظف</option>
                                    @foreach ($employees as $employee)
                                      <option value="{{ $employee->id }}" {{ $employee->id == $user->employee_id ? 'selected' : '' }}>{{ $employee->name }}</option>
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
                              <input id="password" type="password" class="form-control" name="password" placeholder="@lang('users.password')" >
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                                  <label>تأكيد كلمة المرور</label>
                                  <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور" data-parsley-equalto="#password" >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ التعيرات</button>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-solid">
                  <div class="box-header with-border">
                    <h3 class="box-title">@lang('users.rols_permissions')</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="box-group" id="accordion">
                      <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                      <div class="panel box box-primary">
                        <div class="box-header with-border">
                          <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                @lang('users.roles')
                            </a>
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                          <div class="box-body text-left">
                            @foreach ($roles as $role)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                          {{ $role->name }}
                                          <input type="checkbox" class="flat-red" name="roles[]" value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} />
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
                                @lang('users.permissions')
                            </a>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                          <div class="box-body text-left">
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
                                        <input type="checkbox" class="flat-red {{ $permission->id }}" id="{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
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
                                      <input type="checkbox" class="flat-red {{ $permission->id }}" id="{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
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
                    </div>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ التعيرات</button>
            </div>
        </div>
    </form>
@endsection
@include('partials._select2')

@push('js')
    <script>
      let ids = {!! $user_permissions !!}

      console.log(ids);

      ids.forEach(id => {
        if($('input').attr('id', id)) {
          $('input.'+id).iCheck('check');
        }
      });
    </script>
@endpush

@include('partials._select2')
