<?php

namespace App\Exceptions;

use App\Common\ParamsRules;
use App\Common\Utils;
use App\Library\BLogger;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,

        \App\Exceptions\Exception::class
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
        // parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $log_info = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'request' => $request->all(),
        ];
        if ($exception->getCode() > 2001000) {
            // 业务异常日志
            BLogger::warning($log_info);
        } else {
            BLogger::error($log_info);
        }

        if ($request->ajax()) {
            $code = $exception->getCode();
            $message = $exception->getMessage();

            if ($exception instanceof NotFoundHttpException) {
                $code = BaheException::RESOURCE_NOT_FOUND;
                $message = BaheException::$error_msg[BaheException::RESOURCE_NOT_FOUND];
            }
            return Utils::sendJsonResponse($code, $message);
        }

        if ($exception instanceof ValidationException) {
            return redirect()->back();
        } elseif ($exception instanceof NotFoundHttpException) {
            return redirect(ParamsRules::IF_NOT_FOUND);
        } elseif ($exception instanceof FatalErrorException) {
            return redirect(ParamsRules::IF_FATAL_ERROR);
        } elseif ($exception instanceof QueryException) {
            return Utils::renderError(BaheException::$error_msg[BaheException::SYSTEM_ERROR_CODE]);
        } else {
            return Utils::renderError($exception->getMessage());
        }
        //return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
