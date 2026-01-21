<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

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
        // Handle model not found exceptions
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $previous = $e->getPrevious();
                
                if ($previous instanceof ModelNotFoundException) {
                    $model = strtolower(class_basename($previous->getModel()));
                    
                    return response()->json([
                        'success' => false,
                        'message' => "Resource not found",
                        'error' => "The requested {$model} with the given ID does not exist"
                    ], 404);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Not Found',
                    'error' => 'The requested resource does not exist'
                ], 404);
            }
        });
        
        // Handle validation errors more gracefully
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                $errors = $e->errors();
                
                // Check for duplicate email
                if (isset($errors['email']) && str_contains(implode(' ', $errors['email']), 'already been taken')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate email',
                        'error' => 'An attendee with this email address already exists'
                    ], 422);
                }
                
                // Default validation error response
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            }
        });
        
        // Handle database unique constraint violations
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                // Check if it's a unique constraint violation
                if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                    // Extract field name from error message
                    if (str_contains($e->getMessage(), 'attendees.email')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Duplicate email',
                            'error' => 'An attendee with this email address already exists'
                        ], 422);
                    }
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate entry',
                        'error' => 'This record already exists in the database'
                    ], 422);
                }
            }
        });
    })->create();
