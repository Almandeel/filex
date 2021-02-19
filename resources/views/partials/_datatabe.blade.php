{{--  <style>
    .dt-buttons {
        float: left;
        display: inline-block;
    }
</style>  --}}
{{--  <script src="{{ asset('dashboard/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashboard/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">  --}}
{{--  <script src="{{ asset('dashboard/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashboard/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>  --}}
{{--  <script src="{{ asset('dashboard/buttons-datatables/js/datatables.min.js') }}"></script>  --}}
{{--  <script>
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
        },
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
    } );
    $(function(){
        if (!$.fn.DataTable.isDataTable( 'table.datatable' ) ) {
            $('table.datatable').dataTable();
        }
    })
</script>  --}}

{{-- <link rel="stylesheet" type="text/css" href="{{ asset('libs/datatables/datatables.min.css') }}"> --}}
    <script type="text/javascript" src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/datatables/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/datatables/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/datatables/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/datatables/buttons.html5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('libs/datatables/buttons.print.js') }}"></script> --}}
    <style type="text/css">
        .data-table-container {
            padding: 10px;
        }
    
        .dt-buttons .btn {
            margin-left: 3px;
        }

        .dataTables_filter{
            float: right !important;
            margin-bottom: 15px;
        }

        .dt-buttons{
            float: left !important;
            margin-bottom: 15px;
        }
    </style>
    <style media="all">
        .total{
            text-decoration: underline;
        }
    </style>
    <script>
        $(document).ready(function(){
            $.extend( true, $.fn.dataTable.defaults, {
                'paging'      : true,
                'lengthChange': true,
                'searching'   : false,
                'ordering'    : true,
                'info'        : false,
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
                },
                'dom': 'Bfrtip',
                "autoWidth": true,
                "columnDefs": [{
                    "visible": true,
                    "targets": -1
                }],
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i><span>طباعة</span>',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o"></i><span>تصدير Excel</span>',
                        classes: 'btn btn-success'
                    }
                ]
            } );
        $('table.datatable').dataTable({
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    autoPrint: true,
                    text: '<i class="fa fa-print"></i><span>طباعة</span>',
                    footer: true,
                    customize: function ( doc ) {
                        $(doc.document.body).find('h1').css({
                            'text-align': 'center', 'font-size': '15pt',
                            'font-weight': 'bold', 'margin-bottom': '30px',

                        });
                    },
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i><span>اكسل</span>',
                    classes: 'btn btn-success'
                },
            ]
        });
        $('table.datatable-paged').dataTable({
            paging: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    autoPrint: true,
                    text: '<i class="fa fa-print"></i><span>طباعة</span>',
                    footer: true,
                    customize: function ( doc ) {
                        $(doc.document.body).find('h1').css({
                            'text-align': 'center', 'font-size': '15pt',
                            'font-weight': 'bold', 'margin-bottom': '30px',

                        });
                    },
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i><span>اكسل</span>',
                    classes: 'btn btn-success'
                },
            ]
        });
        
        /* custom button event print */
        $(document).on('click', '#btn-print', function(){
           $(".buttons-print")[0].click(); //trigger the click event
        });
    
      });
    </script>