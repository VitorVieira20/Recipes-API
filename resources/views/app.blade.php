<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'pt') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Recipes API</title>

    @viteReactRefresh
    @routes
    @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx", "resources/css/app.css"])
    @inertiaHead

</head>

<body>
    @inertia
</body>

</html>