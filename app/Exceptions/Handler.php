<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->api(null, 401, [['code' => 'USER_NOT_AUTHENTICATED', 'message' => $exception->getMessage()]]);
    }

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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
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
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ApiException) {
            return response()->api($exception->getData(), $exception->getStatusCode(), [['code' => $exception->getCode(), 'message' => $exception->getMessage()]]);
        } else if ($exception instanceof ValidationException) {
            return response()->api(null, $exception->status, $this->parseErrorsFromException($exception));
        }

        return parent::render($request, $exception);
    }

    private function parseErrorsFromException(ValidationException $exception)
    {
        $parsedErrors = [];
        foreach ($exception->errors() as $key => $error) {
            array_push($parsedErrors, [
                'code' => 'VALIDATION_ERROR',
                'message' => $error[0]
            ]);
        }
        return $parsedErrors;
    }
}
