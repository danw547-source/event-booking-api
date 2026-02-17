<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

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
        // Technique: centralized exception-to-response translation.
        // Why applied: keeps controllers focused on request flow while ensuring uniform API error responses.
        $exceptions->render(function (ModelNotFoundException $exception): JsonResponse {
            return response()->json([
                'error' => 'Resource not found',
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (NotFoundHttpException $exception): JsonResponse {
            return response()->json([
                'error' => 'Resource not found',
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (\DomainException $exception): JsonResponse {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        });

        $exceptions->render(function (\Throwable $exception): JsonResponse|null {
            if (!request()->is('api/*')) {
                return null;
            }

            return response()->json([
                'error' => 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
