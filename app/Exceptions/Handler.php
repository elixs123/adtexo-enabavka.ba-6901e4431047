<?php

namespace App\Exceptions;

use App\Mail\Exception\ErrorMail;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * Class Handler
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            // Send error mail
            $this->sendErrorMail($exception, request());
        }
        
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
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

        return redirect()->guest('login');
    }
    
    /**
     * Send error mail.
     *
     * @param \Exception $e
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function sendErrorMail(Exception $e, $request)
    {
        // if (config('app.env') == 'production') {
        //     (new ErrorMail(get_class_name($e), $request, $e->getMessage(), $e->getFile(), $e->getLine()))->sendMail();
        // }
    }
}
