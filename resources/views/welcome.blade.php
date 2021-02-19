<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/AdminLTE-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/restaurant/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Cairo', sans-serif !important;
        }

        .inner {
            padding: 26px !important;
        }

        h3 {
            font-size: 30px !important;
            font-weight: normal;
        }

    </style>

</head>

<body>
    <div class="container">
        <h1 class="text-center">{{ env('APP_NAME') }}</h1>

        <section class="content">
            <div class="row">
                @permission('settings-read')
                    <div class="col-lg-12 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-aqua text-right">
                            <div class="inner">
                                <h3> نظام الادارة</h3>
                            </div>
                            <div class="icon pull-right">
                                <i class="fa fa-list"></i>
                            </div>
                            <a href="{{ route('management.index') }}" class="small-box-footer"> <i
                                    class="fa fa-arrow-circle-right"></i> عرض </a>
                        </div>
                    </div>
                @endpermission
            </div>
            <div class="row">

                @permission('menus-read')
                    <div class="col-lg-4 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-aqua text-right">
                            <div class="inner">
                                <h3> نظام المطاعم</h3>
                            </div>
                            <div class="icon pull-right">
                                <i class="{{ config('restaurant.icon') }}"></i>
                            </div>
                            <a href="{{ route('restaurant.dashboard') }}" class="small-box-footer"> <i
                                    class="fa fa-arrow-circle-right"></i> عرض </a>
                        </div>
                    </div>
                @endpermission

                @permission('subscriptions-read')
                    <div class="col-lg-4 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-aqua text-right">
                            <div class="inner">
                                <h3> نظام الاشتراكات</h3>
                            </div>
                            <div class="icon pull-right">
                                <i class="fa fa-list"></i>
                            </div>
                            <a href="{{ route('subscription.dashboard') }}" class="small-box-footer">
                                <i class="fa fa-arrow-circle-right"></i> عرض </a>
                        </div>
                    </div>
                @endpermission

                @permission('pos-read')
                    @if (auth()->user()->id != 1)
                        <div class="col-lg-4 col-xs-12">
                            <!-- small box -->
                            <div class="small-box bg-aqua text-right">
                                <div class="inner">
                                    <h3>نافذة البيع</h3>
                                </div>
                                <div class="icon pull-right">
                                    <i class="fa fa-list"></i>
                                </div>
                                <a href="{{ route('market.pos') }}" class="small-box-footer"> <i
                                        class="fa fa-arrow-circle-right"></i> عرض </a>
                            </div>
                        </div>
                    @endif
                @endpermission
            </div>
        </section>
    </div>

    <script src="{{ asset('dashboard/js/jquery.min.js') }}"></script>
</body>

</html>
