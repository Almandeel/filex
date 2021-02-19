<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} {{ isset($title) ? ' | ' . $title : '' }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{--<!-- Bootstrap 3.3.7 -->--}}
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/parsley.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.css') }}">
    @isset($printable)
        <link rel="stylesheet" href="{{ asset('libs/Print.js/dist/print.min.css') }}">
    @endisset
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.min.css') }}">
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
    <link rel="stylesheet" href="{{ asset('dashboard/css/skins/skin-' . (isset($skin) ? $skin : 'blue') . '.css') }}">

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
        .table-hide-header thead {
            display: none;
        }
        @media print {
            .print-hide{
                display: none;
            }
        }
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
    
    <link rel="stylesheet" href="{{ asset('dashboard/css/custom.css') }}">
    @stack('head')

</head>
<body class="{{ isset($body_classes) ? $body_classes : '' }}">
    @stack('body')
    {{--<!-- Bootstrap 3.3.7 -->--}}
    <script src="{{ asset('dashboard/js/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('libs/parsleyjs/parsley.js')}}"></script>
    <script src="{{ asset('libs/parsleyjs/i18n/ar.js')}}"></script>

    @includeWhen(isset($datatable), 'partials._datatabe')
    
    {{--icheck--}}
    <script src="{{ asset('dashboard/plugins/icheck/icheck.min.js') }}"></script>

    {{--<!-- FastClick -->--}}
    <script src="{{ asset('dashboard/js/fastclick.js') }}"></script>

    {{--<!-- Vue js -->--}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{--<!-- Sweetalert2 js -->--}}
    <script src="{{ asset('dashboard/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    @isset($tabletojson)
    <script src="{{ asset('libs/jquery.tabletojson/jquery.tabletojson.min.js') }}"></script>
    @endisset
    @isset($printable)
    <script src="{{ asset('libs/Print.js/dist/print.min.js') }}"></script>
    @endisset
    
    {{--<!-- AdminLTE App -->--}}
    <script src="{{ asset('dashboard/js/adminlte.min.js') }}"></script>
    @stack('foot')
</body>
</html>
