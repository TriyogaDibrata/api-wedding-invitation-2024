<?php

namespace App\Helpers;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        // 'meta' => [
        //     'code' => 200,
        //     'status' => 'success',
        //     'message' => null,
        // ],
        'code' => 200,
        'success' => true,
        'message' => null,
        'result' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null)
    {
        self::$response['success'] = true;
        self::$response['message'] = $message;
        self::$response['result'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    /**
     * Give error response.
     */
    public static function error($message = null, $code = 400)
    {
        // self::$response['meta']['status'] = 'error';
        // self::$response['meta']['code'] = $code;
        // self::$response['meta']['message'] = $message;
        self::$response['success'] = false;
        self::$response['message'] = $message;
        self::$response['code'] = $code;


        return response()->json(self::$response, self::$response['code']);
    }
}
