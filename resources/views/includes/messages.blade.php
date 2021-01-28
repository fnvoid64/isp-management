@if (session()->has('message'))
    <script>
        showAlertMsg(`{!! session()->get('message') !!}`);
    </script>
@endif
