@extends('layouts.dashboard.app')

@section('title')
    تعديل مشرف
@endsection

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>تعديل مشرف</h3>
        </div>
        @include('partials._errors')
        <div class="box-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" class="form-control" name="username" value="{{ $user->username }}" placeholder="اسم المستخدم" required>
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="رقم الهاتف" required>
                </div>
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" class="form-control" name="password" value="" placeholder="كلمة المرور">
                </div>
                <button type="submit" class="btn btn-primary">تعديل</button>
            </form>
        </div>
    </div>
@endsection