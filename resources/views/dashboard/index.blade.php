@extends('layouts.dashboard.app')
@section('title')
    لوحة التحكم
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">
                <i class="fa fa-dashboard"></i>
                <span>لوحة التحكم</span>
            </h4>
            <form action="" method="GET" class="form-inline box-tools">
                @csrf
                <label for="from-date">من</label>
                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                <label for="to-date">الى</label>
                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                    <span></span>
                </button>
            </form>
        </div>
    </div>
    <div class="row">    
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow" style="width: 130px; height: 130px; line-height: 130px;">
                    {{--  <i class="ion ion-ios-cart-outline"></i>  --}}
                    <span>{{ $billsItemsCount }}</span>
                </span>
    
                <div class="info-box-content clearfix">
                    <div class="col-xs-12" style="width: 90%;     border-bottom: 1px solid #333;"><h3 class="">المشتريات</h3></div>
                    <div class="col-xs-6" style="width: 46%; border-left: 1px solid #333;">
                        <h4>نقدا</h4>
                        <span class="info-box-number">{{ number_format($billsCashEntries->sum('amount'), 2) }}</span>
                    </div>
                    <div class="col-xs-6" style="width: 46%;;">
                        <h4>بنك</h4>
                        <span class="info-box-number">{{ number_format($billsBankEntries->sum('amount'), 2) }}</span>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    
        <div class="col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green" style="width: 130px; height: 130px; line-height: 130px;">
                    {{--  <i class="ion ion-ios-cart-outline"></i>  --}}
                    <span>{{ $invoicesItemsCount }}</span>
                </span>
    
                <div class="info-box-content clearfix">
                    <div class="col-xs-12" style="width: 90%;     border-bottom: 1px solid #333;"><h3 class="">المبيعات</h3></div>
                    <div class="col-xs-6" style="width: 46%; border-left: 1px solid #333;">
                        <h4>نقدا</h4>
                        <span class="info-box-number">{{ number_format($invoicesCashEntries->sum('amount'), 2) }}</span>
                    </div>
                    <div class="col-xs-6" style="width: 46%;">
                        <h4>بنك</h4>
                        <span class="info-box-number">{{ number_format($invoicesBankEntries->sum('amount'), 2) }}</span>
                    </div>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        @foreach ($items as $units)
        <div class="col-xs-12 col-sm-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $units->first()->itemName() }}</h3>
            
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الوحدة</th>
                                    <th>الكمية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $unit->unitName() }}</td>
                                    <td>{{ $unit->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>الاجمالي</th>
                                    <th>{{ $units->sum('quantity') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
