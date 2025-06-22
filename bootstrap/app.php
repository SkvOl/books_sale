<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use App\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->web(remove: [
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if(isset($_SERVER['REQUEST_URI']) AND str_contains($_SERVER['REQUEST_URI'], '/api')){
            
            
            $exceptions->render(function (Throwable $throwable) {
                $statusCode = method_exists($throwable, 'getStatusCode') ? $throwable->getStatusCode() : 500;
    
                return Response::response([
                    'Message'=>$throwable->getMessage(),
                    'Info'=>[
                        // 'trace'=>$throwable->getTrace(),
                        'line'=>$throwable->getLine(),
                        'file'=>$throwable->getFile(),
                    ]
                ], $statusCode);
            });
        }


       
    })->create();
