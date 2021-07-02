<?php

namespace App\Core\Exceptions;

use App\Core\Logging\SentryContext;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Xentral\Core\ErrorHandler\ErrorHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

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

    /** @var SentryContext */
    private $sentryContext;

    /**
     * @param Container     $container
     * @param SentryContext $sentryContext
     */
    public function __construct(Container $container, SentryContext $sentryContext)
    {
        parent::__construct($container);

        $this->sentryContext = $sentryContext;
    }

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     *
     * @throws \Exception
     *
     * @return void
     */
    public function report(\Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->sentryContext->addTags();
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @return void
     */
    public function register()
    {
        $errorHandler = new ErrorHandler();
        register_shutdown_function([$errorHandler, 'onShutdown']);
        // Use own error output function
        ini_set('display_errors', true);
        set_error_handler([$errorHandler, 'handleError']);
        set_exception_handler([$errorHandler, 'handleException']);
    }
}
