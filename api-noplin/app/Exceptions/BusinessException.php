<?php

namespace App\Exceptions;

use App\Constants\ErrorMessage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;
use Throwable;


/**
 * Class BusinessException
 * @package App\Exceptions
 */
class BusinessException extends Exception
{

    use ResponseTrait;

    public array $replace = [];

    public string $data = '';

    /**
     * BusinessException constructor.
     * @param string $message
     * @param int $code
     * @param array $replace
     * @param string $data
     * @param Throwable|null $previous
     */
    #[Pure] public function __construct($message = "", $code = 1000, $replace = [], string $data = '', Throwable $previous = null)
    {
        $this->replace = $replace;
        $this->data = $data;
        if (empty($message)) {
            $message = ErrorMessage::all()[$code];
        } elseif ($code == 1000) {
            $code = array_flip(ErrorMessage::all())[$message] ?? 1000;
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     */
    public function report()
    {
        Log::channel('business')->error($this->message, request()->all());
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json($this->responseFormat($this->getCode(), $this->data, $this->getMessage(), $this->replace));
    }
}
