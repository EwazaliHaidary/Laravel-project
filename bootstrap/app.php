<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
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
            fn (Request $request, Throwable $e) =>
                $request->is('api/*') || $request->expectsJson(),
        );

        // Handle unauthenticated API requests (401)
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Authentication required',
                    'errors' => (object) [],
                ], 401);
            }
        });

        // Handle unauthorized API requests (403)
        $exceptions->render(function (
            AuthorizationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage()
                        ?: 'You are not allowed to do that',
                    'errors' => (object) [],
                ], 403);
            }
        });

        // Handle missing resources and unknown API endpoints (404)
        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Resource or endpoint not found',
                    'errors' => (object) [],
                ], 404);
            }
        });
    })
    ->create();
