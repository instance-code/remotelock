<?php

namespace InstanceCode\Remotelock\Traits;

use InstanceCode\Remotelock\Enums\ErrorType;

trait Response
{
    public function response(array $items = [], ErrorType $status = ErrorType::HTTP_OK)
    {
        $data = array_key_exists('data', $items) ? $items['data'] : [];
        $message = array_key_exists('message', $items) ? $items['message'] : $status->label();
        $code = array_key_exists('code', $items) ? $items['code'] : $status->name;

        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $status->value);
    }
}
