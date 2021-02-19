@extends('layouts.dashboard.app')

@section('title')
    تعديل قسم 
@endsection


@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> لوحة التحكم</li>
        </ol>
    </section>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"> تعديل : قسم </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="tab-pane" id="name_tab">
                                        <label for="name">الاسم</label>
                                        <input type="text" name="name" class="form-control" placeholder="الاسم" value="{{ $category->name }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="tab-pane" id="name_tab">
                                        <label for="name">القسم الرئيسي</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">قسم رئيسي</option>
                                            @foreach ($categories as $main_category)
                                                <option value="{{ $main_category->id }}" {{ $category->parent_id == $main_category->id ? "selected" : "" }}>{{ $main_category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>    
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-6 col-md-3" style="padding-top: 4px;">
                                <input type="radio" name="next" value="back" checked="true">
                                <span>حفظ و تعديل </span>
                            </label>
                            <label class="col-xs-12 col-sm-6 col-md-3" style="padding-top: 4px;">
                                <input type="radio" name="next" value="list">
                                <span>حفظ و عرض على القائمة</span>
                            </label>
                            <span class="col-xs-12 col-sm-12 col-md-6">
                                <button type="submit" class="btn btn-primary">تعديل</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection