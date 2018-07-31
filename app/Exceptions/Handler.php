<?php

namespace App\Exceptions;

use App\Foundation\Exception\ExceptionReport;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport
        = [
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Session\TokenMismatchException::class,
            \Illuminate\Validation\ValidationException::class,
        ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report( Exception $exception )
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render( $request, Exception $exception )
    {

        if ($request->is('api/*')) {
            $response = [];
            $error    = $this->convertExceptionToResponse($exception);
            $response[ 'code' ] = $error->getStatusCode();
            $response[ 'message' ]  = 'something error';
            if (config('app.debug')) {
                $response[ 'message' ] = $exception->getMessage() ?: 'something error';
                if ($error->getStatusCode() >= 500) {
                    if (config('app.debug')) {
                        $response[ 'trace' ]   = $exception->getTraceAsString();
                        $response[ 'code' ] = $error->getStatusCode();
                    }
                }
            }
            $response[ 'data' ] = [];

            return response()->json($response, $error->getStatusCode());
        }
        else {
            return parent::render($request, $exception);
        }


    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated( $request, AuthenticationException $exception )
    {
        if ($request->expectsJson()) {
            return response()->json([ 'error' => 'Unauthenticated.' ], 401);
        }


        return redirect()->guest(route('login'));
    }
}
