@if ($errors->any())
    <script>
        showAlertMsg(`{!! implode('<br/>', $errors->all()) !!}`, 'error');
    </script>
@endif
