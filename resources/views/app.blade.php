<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-background">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @cspMetaTag(App\Policies\ContentSecurityPolicy::class)
        <meta name="referrer" content="origin-when-cross-origin" />

        <title inertia>{{ config('app.name', 'Ignite') }}</title>

        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

        <!-- Scripts -->
        @routes(false, csp_nonce())
        <script>
            Ziggy.url = '{{ env('APP_URL') }}'
        </script>
        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased min-h-full flex flex-col [overflow-anchor:none]" scroll-region>
        @inertia
    </body>
</html>
