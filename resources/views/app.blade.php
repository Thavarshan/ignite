<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-background text-foreground">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- @cspMetaTag(App\Policies\ContentSecurityPolicy::class) --}}

    <title inertia>{{ config('app.name', 'Ignite') }}</title>

    <link rel="icon" href="{{ asset('favicon.jpg') }}" type="image/x-icon">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Scripts -->
    @routes()
    <script>
        Ziggy.url = '{{ env('APP_URL') }}'
    </script>
    @vite(['resources/js/app.ts'])
    @inertiaHead
</head>

<body class="font-sans antialiased leading-normal min-h-full flex flex-col [overflow-anchor:none]" scroll-region>
    @inertia
</body>

</html>
