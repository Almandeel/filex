@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    إضافة
@endsection

@section('content')
<form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="box">
            <div class="box-header">
                إضافة
            </div>
            <div class="box-header text-right">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="الاسم" required>
                </div>
                <div class="form-group" dir="ltr">
                    <h2>الصلاحيات</h2>
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
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">إضافة</button>
            </div>
        </div>
    </form>
@endsection
