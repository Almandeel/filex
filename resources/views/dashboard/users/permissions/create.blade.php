@extends('layouts.dashboard.app')

@section('title')
    إضافة مشرف
@endsection


@section('content')
<form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="box">
            <div class="box-header">

            </div>
            <div class="box-header">
                <div class="form-group">
                    <label>اسم العرض</label>
                    <input type="text"  class="form-control" name="name" value="{{ old('name') }}" placeholder="الاسم" required>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </form>
@endsection