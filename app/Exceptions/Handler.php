<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
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
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report ( Throwable $exception )
    {
        parent::report( $exception );
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $exception
     * @return Response
     */
    public function render ( $request, Throwable $exception )
    {
        return $this->handleException( $request, $exception );
    }

    public function handleException ( $request, $exception )
    {
        if ( $exception instanceof ModelNotFoundException ) {
            return $this->errorResponse( GET_FAILED, 'resource not found.', 404 );
        }

        if ( config( 'app.debug' ) ) {
            return parent::render( $request, $exception );
        }
    }

    protected function unauthenticated ( $request, AuthenticationException $exception )
    {
        if ( $request->expectsJson() ) {
            return $this->errorResponse( AUTHENTICATED_FAILED, 'unauthenticated user.', 401 );
        }
        return parent::unauthenticated( $request, $exception ); // TODO: Change the autogenerated stub
    }
}
