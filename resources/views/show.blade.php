<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="/blanket/assets/favicon-32x32.png">
    <title>Laravel Blanket</title>
    <script>
        window.blanket_logs_per_page = '{{ config('blanket.logs_per_page') }}'
        window.blanket_base_url = '{{ url(config('blanket.path')) }}'
        window.blanket_app_env = '{{ app()->environment() }}'
    </script>
    <script defer="defer" src="{{asset('/vendor/blanket/js/chunk-vendors.js')}}" type="module"></script>
    <script defer="defer" src="{{asset('/vendor/blanket/js/app.js')}}" type="module"></script>
    <link href="{{asset('/vendor/blanket/css/chunk-vendors.css')}}" rel="stylesheet">
    <link href="{{asset('/vendor/blanket/css/app.css')}}" rel="stylesheet">
</head>
<body>
<noscript><strong>We're sorry but app doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
<div id="app"></div>
</body>
</html>