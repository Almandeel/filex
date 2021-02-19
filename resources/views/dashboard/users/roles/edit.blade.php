@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    تعديل مشرف
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>تعديل مشرف</h3>
        </div>
        @include('partials._errors')
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            {{ method_field('PUT') }}
            <div class="box-header text-right">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" class="form-control" name="name" value="{{ $role->name }}" placeholder="الاسم" required>
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
                                  <input type="checkbox" class="{{ $permission->id }} flat-red"   id="{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
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
                                <input type="checkbox" class="flat-red"   name="permissions[]" value="{{ $permission->id }}">
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
                <button type="submit" class="btn btn-primary">تعديل</button>
            </div>
    </div>
@endsection

@push('js')
<script>
    let ids = {!! $role_permissions !!}

    console.log(ids);

    ids.forEach(id => {
      if($('input').attr('id', id)) {
        $('input.'+id).iCheck('check');
      }
    });
</script>
@endpush



