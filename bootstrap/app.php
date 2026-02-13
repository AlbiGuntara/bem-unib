<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Inertia\Inertia;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        // 403 - Forbidden (permission / policy)
        $exceptions->render(function (
            UnauthorizedException|AccessDeniedHttpException|AuthorizationException $e,
            $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            return Inertia::render('Errors/403')
                ->toResponse($request)
                ->setStatusCode(403);
        });

        // 404 - Not Found
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Not Found',
                ], 404);
            }

            return Inertia::render('Errors/404')
                ->toResponse($request)
                ->setStatusCode(404);
        });

        // 499 - Copyright Integrity
        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 499) {

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Application Integrity Violation',
                    ], 499);
                }

                return Inertia::render('Errors/499')
                    ->toResponse($request)
                    ->setStatusCode(499);
            }
        });
    })
    ->create();
