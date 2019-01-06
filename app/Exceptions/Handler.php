<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        MethodNotAllowedHttpException::class,
        NotFoundHttpException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        if($exception instanceof \Illuminate\Database\QueryException){
            Log::critical("Database exception: \n ".$exception->getMessage());
            return;
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return mixed
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ModelNotFoundException){
            return new JsonResponse([
                'success' => false,
                'message' => 'Not Found',
                'time' => Carbon::now()
            ], 404);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return new JsonResponse([
                'success' => false,
                'message' => 'Method '.$request->method().' not Allowed',
                'time' => Carbon::now()
            ], 405);
        }
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
            return new JsonResponse([
                'success' => false,
                'message' => 'Not Found',
                'time' => Carbon::now()
            ], 400);
        }
        if($exception instanceof \Illuminate\Database\QueryException){
            return new JsonResponse([
                'success' => false,
                'message' => 'Something went wrong. Please contact support',
                'time' => Carbon::now()
            ], 500);
        }
        return parent::render($request, $exception);
    }
}
