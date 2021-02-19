@php
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="document.pdf"');
    header('Content-Transfer-Encoding: binary');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('name')</title>
    <style>
        body {
            /* font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            */
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }
        h1 {
            text-align:center;
            line-height:1;
        }
        @page {
            header: page-header;
            footer: page-footer;
        }
        * {
            padding: 0;
            margin:0;
            box-sizing: border-box;
        }
        .table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
    border-spacing: 0;
    }

    .table th,
    .table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #eceeef;
    text-align: center !important;
    }

    .table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #eceeef;
    }

    .table tbody + tbody {
    border-top: 2px solid #ddd;
    }

    .table .table {
    background-color: #fff;
    }

    .table-sm th,
    .table-sm td {
    padding: 0.3rem;
    }

    .table-bordered {
    border: 1px solid #ddd;
    }

    .table-bordered th,
    .table-bordered td {
    border: 1px solid #ddd;
    }

    .table-bordered thead th,
    .table-bordered thead td {
    border-bottom-width: 2px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
    }

    .table-active,
    .table-active > th,
    .table-active > td {
    background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
    background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover > td,
    .table-hover .table-active:hover > th {
    background-color: rgba(0, 0, 0, 0.075);
    }

    .table-success,
    .table-success > th,
    .table-success > td {
    background-color: #dff0d8;
    }

    .table-hover .table-success:hover {
    background-color: #d0e9c6;
    }

    .table-hover .table-success:hover > td,
    .table-hover .table-success:hover > th {
    background-color: #d0e9c6;
    }

    .table-info,
    .table-info > th,
    .table-info > td {
    background-color: #d9edf7;
    }

    .table-hover .table-info:hover {
    background-color: #c4e3f3;
    }

    .table-hover .table-info:hover > td,
    .table-hover .table-info:hover > th {
    background-color: #c4e3f3;
    }

    .table-warning,
    .table-warning > th,
    .table-warning > td {
    background-color: #fcf8e3;
    }

    .table-hover .table-warning:hover {
    background-color: #faf2cc;
    }

    .table-hover .table-warning:hover > td,
    .table-hover .table-warning:hover > th {
    background-color: #faf2cc;
    }

    .table-danger,
    .table-danger > th,
    .table-danger > td {
    background-color: #f2dede;
    }

    .table-hover .table-danger:hover {
    background-color: #ebcccc;
    }

    .table-hover .table-danger:hover > td,
    .table-hover .table-danger:hover > th {
    background-color: #ebcccc;
    }

    .thead-inverse th {
    color: #fff;
    background-color: #292b2c;
    }

    .thead-default th {
    color: #464a4c;
    background-color: #eceeef;
    }

    .table-inverse {
    color: #fff;
    background-color: #292b2c;
    }

    .table-inverse th,
    .table-inverse td,
    .table-inverse thead th {
    border-color: #fff;

    }

    .table-inverse.table-bordered {
    border: 0;
    }

    .table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .table-responsive.table-bordered {
    border: 0;
    }
    .row {
    margin-left: -15px;
    margin-right: -15px;
    }

    .col-md-6 {
    float:right;
    width:50%;
    }
    .form-group {
        margin-bottom:15px;
    }
    .col-md-4{
        width:33.33333333%;
        float:right;
    }
    </style>
</head>
<body dir="rtl">

    <header>
        <div>
            <h1>@yield('name')</h1>
        </div>
    </header>

    @yield('content')

</body>
</html>