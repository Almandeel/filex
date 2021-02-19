@push('phone-number')
    <link rel="stylesheet" href="{{ asset('css/intlTelInput.css') }}">
    <script src="{{ asset('js/intlTelInput-jquery.js') }}"></script>
    <script src="{{ asset('js/intlTelInput.js') }}"></script>
    <script>
        var input = document.querySelector("#phone");
        window.intlTelInput(input);
    </script>
@endpush
