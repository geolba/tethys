<?php

namespace App\Exceptions;

// use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'password',
        'password_confirmation',
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        return parent::report($exception);
    }

      /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable  $ex)
    {
        if ($ex instanceof \Illuminate\Auth\Access\AuthorizationException) {
            // return $this->errorResponse($exception->getMessage(), 403);
            return redirect('/login', 302)
                   ->with('error', 'The form has expired due to inactivity. Please try again');
            // redirect()->guest($ex->redirectTo() ?? route('login'));
        }
        // if ($ex instanceof AuthorizationException) {
        //     // return $this->errorResponse($exception->getMessage(), 403);
        //     return redirect('/login', 302)
        //         //    ->back()
        //         //    ->withInput($request->except(['password', 'password_confirmation']))
        //            ->with('error', 'The form has expired due to inactivity. Please try again');
        // }

        // if ($ex instanceof \Illuminate\Session\TokenMismatchException) {
        //     // return redirect('/login', 302)
        //     //     //    ->back()
        //     //     //    ->withInput($request->except(['password', 'password_confirmation']))
        //     //        ->with('error', 'The form has expired due to inactivity. Please try again');
        //     return response()->json(array(
        //         'success' => true,
        //         //'redirect' =>  route('settings.document.edit', ['id' => $dataset->server_state]),
        //         'redirect' =>  route('login')
        //     ));
        // }
        return parent::render($request, $ex);
    }
}
