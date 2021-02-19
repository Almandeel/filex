@if (session('success'))
    <script>
        new Noty({
            type: 'success',
            // layout: 'topRight',
            text: "@lang(session('success'))",
            timeout: 2000,
            killer: true
        }).show();
    </script> 
@endif

@if (session('error'))
    <script>
        new Noty({
            type: 'error',
            // layout: 'topRight',
            text: "@lang(session('error'))",
            timeout: 2000,
            killer: true
        }).show();
        //error
    </script> 
@endif