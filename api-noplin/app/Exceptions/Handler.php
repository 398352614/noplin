<?php

namespace App\Exceptions;

use App\Constants\ErrorCode;
use App\Manager\Mail\SendException;
use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [

    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            dd($e);
            Mail::to(config('project.root.email'))->send(new SendException($e));
        });
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json($this->responseFormat(ErrorCode::CLASS_NOT_EXIST, '', '类不存在'));
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json($this->responseFormat(ErrorCode::HTTP_METHOD_ERROR, '', '请求方式错误'));
        }

        if ($e instanceof AuthenticationException) {
            return response()->json($this->responseFormat(ErrorCode::AUTH_ERROR, '', '用户认证失败'));
        }

        if ($e instanceof TokenExpiredException) {
            return response()->json($this->responseFormat(ErrorCode::TOKEN_EXPIRED, '', '用户认证过期'));
        }

        return parent::render($request, $e);
    }
}
