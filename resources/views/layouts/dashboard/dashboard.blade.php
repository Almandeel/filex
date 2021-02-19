@php
    $skin = isset($skin) ? $skin : 'blue';
    $skin_class = 'skin-' . $skin;
    $body_classes = 'hold-transition sidebar-mini ' .$skin_class;
@endphp
@extends('layouts.dashboard.base', [
    'skin' => $skin,
    'body_classes' => $body_classes
])
@push('head')
    <link rel="stylesheet" href="{{ asset('fonts/restaurant/style.css') }}">
    <style>
        .table-busy,
        .table-free {
            display: inline-block;
            position: relative;
            width: 100px;
            height: 100px;
            /* line-height: 100px; */
            text-align: center;
            background-color: #fff;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            /* padding: 15px; */
            border-radius: 50%;
            border: 1px solid transparent;
            transition: 0.3s all ease-in-out
        }
    
        a.table-busy:hover,
        a.table-free:hover {
            transform: scale(1.2);
        }
    
        .table-busy {
            background-image: url("{{ asset('pos/assets/images/table-busy.svg') }}");
            /* background-color: rgb(255 239 210); */
            border-color: orange;
        }
    
        .table-free {
            background-image: url("{{ asset('pos/assets/images/table-free.svg') }}");
            /* background-color: rgb(242 255 242); */
            border-color: green;
        }
    
        .table-busy .table-counter,
        .table-free .table-counter {
            font-size: 5rem;
            font-weight: bold;
            color: #ffffff;
            display: block;
            position: absolute;
            top: -15px;
            left: -15px;
        }
    
        .table-free .table-counter {
            text-shadow: -1px -1px green, 1px 1px green, -2px -2px green, 2px 2px green;
        }
    
        .table-busy .table-counter {
            text-shadow: -1px -1px orange, 1px 1px orange, -2px -2px orange, 2px 2px orange;
        }
        .sidebar-menu.tree li > a > [class^="icon-"], [class*=" icon-"] {
            transition: all 0.3s ease;
        }
        .sidebar-menu.tree li.active > a > [class^="icon-"], .sidebar-menu.tree li.active > a > [class*=" icon-"],
        .sidebar-menu.tree li > a:hover > [class^="icon-"], .sidebar-menu.tree li > a:hover > [class*=" icon-"] {
            padding: 10px;
            border-radius: 50%;
            background-color: #b8c7ce;
            color: #222d32;
            font-size: 2.5rem;
            margin-left: 5px;
        }
    </style>
    @isset($datatable)
        <link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    @endisset
    @stack('dashboard_head')
@endpush
@push('body')
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
                    @stack('dashboard_navbar_items')
                </ul>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav" id="vue">
                        @stack('dashboard_navbar_left_items')
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

        <!-- Main Sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ asset('dashboard/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ auth()->user()->username }}</p>
                    </div>
                </div>
                <ul class="sidebar-menu" data-widget="tree">
                    @stack('dashboard_sidebar_items')
                </ul>
            </section>
        </aside>

        <div class="wrapper">
            <div class="content-wrapper">
                <section class="content">
                    <section class="content-header" style="margin-bottom: 15px;">
                        <h1>.</h1>
                        <ol class="breadcrumb">
                            <li class="{{ !isset($crumbs) && request()->segment(1) == '' ? 'active' : '' }}"><a href="/"><i class="fa fa-dashboard"></i><span>لوحة التحكم</span></a></li>
                            @if ((request()->segment(1) == 'restaurant'))
                                <li class="{{ !isset($crumbs) && request()->segment(1) == 'restaurant' ? 'active' : '' }}"><a href="{{ route('restaurant.dashboard') }}">
                                    <i class="{{ config('restaurant.icon') }}"></i>
                                    <span>{{ config('restaurant.name') }}</span>
                                </a></li>
                            @elseif ((request()->segment(1) == 'cashier'))
                                <li class="{{ !isset($crumbs) && request()->segment(1) == 'cashier' ? 'active' : '' }}"><a href="{{ route('cashier.dashboard') }}">
                                    <i class="{{ config('cashier.icon') }}"></i>
                                    <span>{{ config('cashier.name') }}</span>
                                </a></li>	
                            @endif
                            @if (isset($crumbs))
                                @foreach ($crumbs as $crumb)
                                    <li class="{{ $loop->index == (count($crumbs) - 1) ? 'active' : '' }}">
                                        @if (array_key_exists('url', $crumb))
                                            <a href="{{ $crumb['url'] }}">
                                                @if (array_key_exists('icon', $crumb))
                                                    <i class="{{ $crumb['icon'] }}"></i>
                                                @endif
                                                @if (array_key_exists('title', $crumb))
                                                    <span>{{ $crumb['title'] }}</span>
                                                @endif
                                            </a>
                                        @else
                                            <span>
                                                @if (array_key_exists('icon', $crumb))
                                                    <i class="{{ $crumb['icon'] }}"></i>
                                                @endif
                                                @if (array_key_exists('title', $crumb))
                                                    <span>{{ $crumb['title'] }}</span>
                                                @endif
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            @endif                            
                        </ol>
                    </section>
                    @include('partials._errors')
                    @stack('dashboard_content')
    
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
    
        {{-- <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; 2014-2016
                <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </footer> --}}
    
    </div><!-- end of wrapper -->
@endpush
@push('foot')
    {{--  @isset($datatable)
        <script src="{{ asset('dashboard/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script>
            $.extend( true, $.fn.dataTable.defaults, {
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                'oLanguage'    : {
                    "sEmptyTable":  "@lang('table.sEmptyTable')",
                    "sInfo":    "@lang('table.sInfo')",
                    "sInfoEmpty":   "@lang('table.sInfoEmpty')",
                    "sInfoFiltered":    "@lang('table.sInfoFiltered')",
                    "sInfoPostFix": "@lang('table.sInfoPostFix')",
                    "sInfoThousands":   "@lang('table.sInfoThousands')",
                    "sLengthMenu":  "@lang('table.sLengthMenu')",
                    "sLoadingRecords":  "@lang('table.sLoadingRecords')",
                    "sProcessing":  "@lang('table.sProcessing')",
                    "sSearch":  "@lang('table.sSearch')",
                    "sZeroRecords": "@lang('table.sZeroRecords')",
                    "oPaginate": {
                        "sFirst":   "@lang('table.sFirst')",
                        "sLast":    "@lang('table.sLast')",
                        "sNext":    "@lang('table.sNext')",
                        "sPrevious":    "@lang('table.sPrevious')"
                    },
                    "oAria": {
                        "sSortAscending":   "@lang('table.sSortAscending')",
                        "sSortDescending":  "@lang('table.sSortDescending')"
                    }
                }
            } );
            $(function(){
                if (!$.fn.DataTable.isDataTable( 'table.datatable' ) ) {
                    $('table.datatable').dataTable();
                }
            })
            $(function(){
                if (!$.fn.DataTable.isDataTable( 'table.dataexport' ) ) {
                    $('table.dataexport').dataTable({
                        'dom': 'Bfrtip',
                        buttons: [
                            {
                                extend: 'print',
                                text: 'طباعة المحتوى'
                            },
                            {
                                extend: 'excel',
                                text: 'تصدير Excel'
                            },
                        ]
                    });
                }
            })
        </script>
    @endisset  --}}
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
    <link href="{{ asset('dashboard/plugins/jquery-editable-select/dist/jquery-editable-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('dashboard/plugins/jquery-editable-select/dist/jquery-editable-select.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select.editable').editableSelect();
            
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
    @stack('dashboard_js')
    @if(isset($modals))
        @foreach ($modals as $modal)
            @include('dashboard.modals.' . $modal)
        @endforeach
    @endif

    {{-- Include dataTable component when variable passed in the page --}}
    {{--  @includeWhen(isset($datatable), 'partials._datatabe')  --}}

    @if(isset($select2))
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
                    language: "ar",
                });
            })
        </script>
    @endif
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        $(function(){
            $(document).on('click', '*[data-toggle=confirm]', function(e){
                e.preventDefault()
                let that = $(this);
                let title = that.data('title') ? that.data('title') : 'حذف';
                let text = that.data('text') ? that.data('text') : 'تأكيد الحذف';
                let icon = that.data('icon') ? that.data('icon') : 'warning';
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'إلغاء',
                    confirmButtonText: 'تأكيد',
                }).then((result) => {
                    if (result.value) {
                        var href_attr = $(this).attr('href');
                        if(that.prop("tagName").toLowerCase() == 'a' && (typeof href_attr !== typeof undefined && href_attr !== false)){
                            window.location.href = href_attr;
                        }
                        else if(that.data('callback')){
                            executeFunctionByName(that.data('callback'), window)
                        }
                        else if(that.data('form')){
                            $(that.data('form')).submit()
                        }
                        else{
                            that.closest('form').submit()
                        }
                    }	
                })
            })
        })
        function sweet(title, text, icon = 'info'){
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'حسنا',
            })
        }
    </script>
    @stack('dashboard_foot')
@endpush
