<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $ex)
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
