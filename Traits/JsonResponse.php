<?php

namespace hollis1024\lumen\struct\Traits;


Trait JsonResponse
{
    function throw_e($error = 'error', $code = 500)
    {
        throw new \hollis1024\lumen\struct\Exceptions\BusinessException($error, $code);
    }

    function throw_on($bool, $error = 'error', $code = 500)
    {
        if ($bool) {
            $this->throw_e($error, $code);
        }
        return $bool;
    }

    function throw_empty($empty, $error = 'error', $code = 500)
    {
        if (empty($empty)) {
            $this->throw_e($error, $code);
        }
        return $empty;
    }

    function ok($data = [], $code = 200, $message = 'success')
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ]);
    }

    function fail($code = 500, $message = 'error')
    {
        return response()->json([
            'status' => -1,
            'data' => null,
            'message' => $message,
            'code' => $code
        ]);
    }
}