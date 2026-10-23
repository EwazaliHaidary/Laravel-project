
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Return JSON responses for API requests
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*')
                || $request->expectsJson(),
        );

        // Handle missing database records
        $exceptions->render(function (
            ModelNotFoundException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Resource not found',
                    'errors' => (object) [],
                ], 404);
            }
        });

        // Handle unknown API endpoints
        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Endpoint not found',
                    'errors' => (object) [],
                ], 404);
            }
        });

    })
    ->create();
