<?php

namespace App\Services;

use App\Services\ResponseBuilder;
use Closure;
use Exception;

class BaseController
{
    protected ?ResponseBuilder $response = null;

    public function __construct() {}

    protected function handleRequest(Closure $callback)
    {
        $this->response ??= app(ResponseBuilder::class);
        try {
            $response = $callback();

            if (is_array($response) && isset($response['type'], $response['data'])) {
                $message = $response['message'] ?? null;

                return match ($response['type']) {
                    'success' => $this->response->success($response['data'], $message),
                    'created' => $this->response->created($response['data'], $message),
                    'unAuthorized' => $this->response->unAuthorized($response['data'], $message),
                    default => $this->response->success($response['data'], $message),
                };
            }

            return $this->response->success($response['data'], $response['message']);
        } catch (Exception $e) {
            $onFail = is_array($response ?? []) && isset($response['fail']) ? $response['fail'] : 'error';

            return match ($onFail) {
                'unAuthorized' => $this->response->unAuthorized($e),
                default => $this->response->error($e),
            };
        }
    }
}
