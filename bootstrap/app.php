<?php

use App\Http\Middleware\BlockJsonRequests;
use App\Http\Middleware\BlockWebRequests;
use App\Http\Middleware\CertificateTransparencyPolicy;
use App\Http\Middleware\ContentTypeOptions;
use App\Http\Middleware\FormatPhoneNumber;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PermissionsPolicy;
use App\Http\Middleware\RemoveHeaders;
use App\Http\Middleware\SetReferrerPolicy;
use App\Http\Middleware\StrictTransportSecurity;
use App\Http\Middleware\ValidateJsonBody;
use App\Http\Middleware\XSSGuard;
use App\Http\Middleware\XSSMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Csp\AddCspHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web/routes.php',
        api: __DIR__ . '/../routes/api/routes.php',
        commands: __DIR__ . '/../routes/console/routes.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            RemoveHeaders::class,
            SetReferrerPolicy::class,
            XSSMode::class,
            StrictTransportSecurity::class,
            CertificateTransparencyPolicy::class,
            ContentTypeOptions::class,
            PermissionsPolicy::class,
            XSSGuard::class,
            FormatPhoneNumber::class,
        ]);

        $middleware->web(append: [
            BlockJsonRequests::class,
            HandleInertiaRequests::class,
            AddCspHeaders::class,
        ]);

        $middleware->api(append: [
            EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class . ':api',
            SubstituteBindings::class,
            BlockWebRequests::class,
            ValidateJsonBody::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
