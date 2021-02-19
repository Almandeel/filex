<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{--<!-- Bootstrap 3.3.7 -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/custom.css') }}">

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/parsley.css')}}">
        <link rel="stylesheet" href="{{ asset('dashboard/css/rtl.css') }}">

        <style>
            body, h1, h2, h3, h4, h5, h6 {
                font-family: 'Cairo', sans-serif !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                direction: rtl;
            }
            .content-header>.breadcrumb{
                float: right;
                left: auto;
                right: 10px;
            }
        </style>
    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard/css/AdminLTE.min.css') }}">
    @endif

    <style>
        .d-inline{display: inline;}
        .d-inline-block{display: inline-block;}
        .btn-submit:hover{cursor: pointer;}
        .mr-2{
            margin-right: 5px;
        }
        .btn-search{
            margin-bottom: 10px;
            display: inline-block;
        }
        .mb-10{
            margin-bottom: 10px;
        }
        .mr-0{
            margin-right: 0;
        }
        .ml-0{
            margin-left: 0;
        }
        .pr-0{
            padding-right: 0;
        }
        .pl-0{
            padding-left: 0;
        }
        .reset{
            padding: 0;
            margin: 0;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
        {vertical-align: middle;}
        label.required:after{
            content: "*";
            color: red;
        }
        table td.options, table td.options{
            text-align: center;
        }
        .modal-title{display: inline-block;}
        form.btn label:hover{cursor: pointer;}
    </style>
    {{--<!-- jQuery 3 -->--}}
    <script src="{{ asset('dashboard/js/jquery.min.js') }}"></script>

    @stack('select2')
    {{--noty--}}
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/noty/noty.css') }}">
    <script src="{{ asset('dashboard/plugins/noty/noty.min.js') }}"></script>

    {{--<!-- iCheck -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/icheck/all.css') }}">

    {{--html in  ie--}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    @stack('css')

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

    <header class="main-header">

        {{--<!-- Logo -->--}}
        <a href="{{ route('dashboard.index') }}" class="logo">
            {{--<!-- mini logo for sidebar mini 50x50 pixels -->--}}
            <span class="logo-mini"></span>
            <span class="logo-lg"><b>{{ env("app_name") }}</b></span>
        </a>

        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <ul class="nav navbar-nav">
                @permission('reports-read')
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-print"></i>
                        <span>التقارير</span>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @permission('safes-print')
                            <li><a href="{{ route('reports.stores') }}">المخازن</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.safes') }}">الخزن</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.safe') }}">حركة الخزنة</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.quantities') }}">الكميات</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.purchases') }}">المشتريات</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.sells') }}">المبيعات</a></li>
                        @endpermission
                        @permission('safes-print')
                            <li><a href="{{ route('reports.profits') }}">الارباح والخسائر</a></li>
                        @endpermission
                    </ul>
                </li>
                @endpermission
                @if (env('APP_DEV'))
                <li><a href="#" class="showResetModal">
                    <i class="fa fa-repeat"></i>
                    <span>اعادة تهيئة النظام</span>
                </a></li>
                @endif
            </ul>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav" id="vue">
                    <notifications-component></notifications-component>
                    <li class="dropdown user user-menu">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('dashboard/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ auth()->user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu">

                            {{--<!-- User image -->--}}
                            <li class="user-header">
                                <img src="{{ asset('dashboard/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ auth()->user()->username }}
                                </p>
                            </li>

                            {{--<!-- Menu Footer-->--}}
                            <li class="user-footer">

                                <div class="row">
                                    <div class="col-md-5">
                                        <a href="{{ route('logout') }}" class="btn btn-default" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">تسجيل خروج</a>
        
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <button style="margin-right:1%" type="button" class="btn btn-default" data-toggle="modal" data-target="#edit_user">
                                            الملف الشخصي
                                        </button>
                                    </div>
                                </div>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

    </header>

    @include('layouts.dashboard._aside')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <h1></h1>
                <section class="content-header" style="position:none;">
                    @yield('breadcrumb')
                </section>
                @include('partials._errors')
                @yield('content')

                <div class="modal fade" id="edit_user">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang('users.profile')</h4>
                        </div>
                        <form action="{{ route('users.profile')}}" method="POST">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>@lang('users.username')</label>
                                    <input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}" placeholder="@lang('users.username')" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>كلمة المرور الحالية</label>
                                    <input type="password" class="form-control" autocomplete="off" name="old_password" placeholder="كلمة المرور الحالية">
                                </div>

                                <div class="form-group">
                                    <label>كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="كلمة المرور الجديدة">
                                </div>

                                <div class="form-group">
                                    <label>تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور" data-parsley-equalto="#password"	>
                                </div>
                                
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">إغلاق</button>
                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </div>
                        </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </section>
        </div>
    </div>
    @if (env('APP_DEV'))
    <div class="modal modal-xl fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="chequesLabel"
        aria-hidden="true">
        <form action="{{ route('dashboard.reset') }}" method="POST" class="modal-dialog" role="document" style="width: 600px;">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetModalLabel">
                        <i class="fa fa-repeat"></i>
                        <span>اعادة تهيئة النظام</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body clearfix">
                    {{-- <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="employees">
                            <span>الموظفين</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="users">
                            <span>المستخدمين</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="roles">
                            <span>الادوار</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="stores">
                            <span>المخازن</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="units">
                            <span>الوحدات</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="items">
                            <span>المنتجات</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" name="safes">
                            <span>الخزن</span>
                        </label>
                    </div> --}}
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="suppliers">
                            <span>الموردين</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="customers">
                            <span>العملاء</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="collaborators">
                            <span>المتعاونون</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="bills">
                            <span>فواتير الشراء</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="invoices">
                            <span>فواتير البيع</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="cheques">
                            <span>الشيكات</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="payments">
                            <span>المدفوعات</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="expenses">
                            <span>المنصرفات</span>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label>
                            <input type="checkbox" checked name="transfers">
                            <span>التحويلات</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-submit-cheque">اكمال العملية</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </form>
    </div>
    @endif
    @include('partials._session')

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.1.0
        </div>
        <strong>كل الحقوق محفوظة &copy; 
            <a href="#"></a></strong> 
    </footer>

</div><!-- end of wrapper -->

{{--<!-- Bootstrap 3.3.7 -->--}}
<script src="{{ asset('dashboard/js/popper.min.js') }}"></script>
<script src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/parsleyjs/parsley.js')}}"></script>
<script src="{{ asset('libs/parsleyjs/i18n/ar.js')}}"></script>

{{--icheck--}}
<script src="{{ asset('dashboard/plugins/icheck/icheck.min.js') }}"></script>

{{--<!-- FastClick -->--}}
<script src="{{ asset('dashboard/js/fastclick.js') }}"></script>

{{--<!-- Vue js -->--}}
<script src="{{ asset('js/app.js') }}"></script>

{{--<!-- AdminLTE App -->--}}
<script src="{{ asset('dashboard/js/adminlte.min.js') }}"></script>

@if (isset($jqueryUI))
    <style>
        tbody tr{cursor: move;}
        .mouseDown:hover{
            cursor: move;
        }
    </style>
    <script src="{{ asset('dashboard/js/jquery-ui.js') }}"></script>
    <script>
        $("tbody tr").on("mousedown", function () {
            $(this).addClass("mouseDown");
        }).on("mouseup", function () {
            $(this).removeClass("mouseDown");
        });
    </script>
@endif

<script>
    $(document).ready(function () {
        $('.btn-cheque-confirm').click(function (e) {
            e.preventDefault();
            let confirmed = confirm('سوف يتم تأكيد الشيك استمرار؟');
            if (confirmed) $(this).closest('form').submit();
        });

        $('.showResetModal').click(function(){
            $('#resetModal').modal('show')
        })
        $('form.prevent-input-submition input').keydown(function (e) {
            if (e.keyCode == 13) {
                var inputs = $(this).parents("form").eq(0).find(":input");
                if (inputs[inputs.index(this) + 1] != null) {
                    inputs[inputs.index(this) + 1].focus();
                }
                e.preventDefault();
                return false;
            }
        });
        $(document).on('change', '.form-submitter', function(){
            $(this).closest('form').submit()
        })
        $('.sidebar-menu').tree();
        $('[data-toggle="popover"]').popover();
        //icheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            // radioClass   : 'iradio_flat-green'
        })

        $('.delete').click(function (e) {

            var that = $(this)

            e.preventDefault();
            Noty.overrideDefaults({
                layout: "topRight",
                animation: {
                    open : 'animated fadeInLeft',
                    close: 'animated fadeOutLeft'
                }
            });
            var n = new Noty({
                text: "تأكيد الحذف",
                type: "warning",
                killer: true,
                buttons: [
                    Noty.button("نعم", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),

                    Noty.button("لا", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });

            n.show();

        });//end of delete


        $('form').parsley();
    
        window.Parsley.on('form:success', function(){
            $('button[type="submit"]').attr('disabled', true)
        })
        

    })
</script>
<script>
    Array.prototype.empty = function() {
        for (var i = 0, s = this.length; i < s; i++) { this.pop(); }
        return this;
    };

    Array.prototype.removeAll = function(item) {
        var result = [];

        for (var i = 0, j = 0, s = this.length; i < s; i++) {
            if (this[i] != item) { result[j++] = this[i]; }
        }

        this.empty();
        for (var i = 0, s = result.length; i < s;) { this.push(result[i++]); }
    };
</script>

@if(isset($snippts))
    <script src="{{ asset('dashboard/js/snippts.js') }}"></script>
@endif
    
@if(isset($modals))
    @foreach ($modals as $modal)
        @include('dashboard.modals.' . $modal)
    @endforeach
@endif

{{-- Include dataTable component when variable passed in the page --}}
@includeWhen(isset($datatable), 'partials._datatabe')


    <link href="{{ asset('dashboard/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dashboard/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dashboard/css/select2-bootstrap.css') }}" rel="stylesheet" />
    <script src="{{ asset('dashboard/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/select2-ar.js') }}"></script>
    <style>
        .input-group>.select2-hidden-accessible:first-child+.select2-container--bootstrap>.selection>.select2-selection,
        .input-group>.select2-hidden-accessible:first-child+.select2-container--bootstrap>.selection>.select2-selection.form-control {
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
        }
    
        .input-group>.select2-container--bootstrap,
        .input-group>.select2-container--bootstrap .input-group-btn,
        .input-group>.select2-container--bootstrap .input-group-btn .btn {
            width: 100% !important;
        }
    
        .input-group .form-control:last-child,
        .input-group-addon:last-child,
        .input-group-btn:last-child>.btn,
        .input-group-btn:last-child>.btn-group>.btn,
        .input-group-btn:last-child>.dropdown-toggle,
        .input-group-btn:first-child>.btn:not(:first-child),
        .input-group-btn:first-child>.btn-group:not(:first-child)>.btn {
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <script>
        $.fn.select2.defaults.set( "theme", "bootstrap" );
          $(function () {
            $('select').select2({
              dir: "rtl",
              dropdownAutoWidth: true,
              language: "ar"
            });
          })
    </script>

    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('js')
</body>
</html>
