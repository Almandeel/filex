@extends('layouts.dashboard.app')

@section('title')
    إضافة مصروف جديد
@endsection


@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> لوحة التحكم</li>
        </ol>
    </section>
    <form action="{{ route('expensestypes.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"> إضافة : مصروف </h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="tab-pane" id="name_tab">
                                <label for="name">الاسم</label>
                                <input type="text" name="name" class="form-control" placeholder="الاسم">
                            </div><!-- /.tab-pane -->
                        </div>    
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-6 col-md-3" style="padding-top: 4px;">
                                <input type="radio" name="next" value="back" checked="true">
                                <span>حفظ و إضافة جديد</span>
                            </label>
                            <label class="col-xs-12 col-sm-6 col-md-3" style="padding-top: 4px;">
                                <input type="radio" name="next" value="list">
                                <span>حفظ و عرض على القائمة</span>
                            </label>
                            <span class="col-xs-12 col-sm-12 col-md-6">
                                <button type="submit" class="btn btn-primary">إضافة</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection