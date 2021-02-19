@push('select2')
    <link href="{{ asset('dashboard/css/select2.min.css') }}" rel="stylesheet"/>  
    <link href="{{ asset('dashboard/css/select2.min.css') }}" rel="stylesheet"/>  
    <link href="{{ asset('dashboard/css/select2-bootstrap.css') }}" rel="stylesheet"/>  
    <script src="{{ asset('dashboard/js/select2.full.min.js') }}"></script> 
    <script src="{{ asset('dashboard/js/select2-ar.js') }}"></script> 
    <style>
      .input-group > .select2-hidden-accessible:first-child + .select2-container--bootstrap > .selection > .select2-selection,
      .input-group > .select2-hidden-accessible:first-child + .select2-container--bootstrap > .selection >
      .select2-selection.form-control {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
      }
      .input-group > .select2-container--bootstrap, .input-group > .select2-container--bootstrap .input-group-btn,
      .input-group > .select2-container--bootstrap .input-group-btn .btn{
        width: 100% !important;
      }
      .input-group .form-control:last-child, .input-group-addon:last-child, .input-group-btn:last-child>.btn,
      .input-group-btn:last-child>.btn-group>.btn, .input-group-btn:last-child>.dropdown-toggle,
      .input-group-btn:first-child>.btn:not(:first-child), .input-group-btn:first-child>.btn-group:not(:first-child)>.btn{
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
@endpush
