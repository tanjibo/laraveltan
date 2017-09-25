<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 5:42 PM
 */

namespace App\Foundation\Helpers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

trait ApiResponse
{

    protected $statusCode = BaseResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode( $statusCode )
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond( $data, $header = [] )
    {
        return Response::json($data, $this->getStatusCode(), $header);
    }

    public function status( $status, array $data, $code = null )
    {
        if ($code) {
            $this->setStatusCode($code);
        }
        $status = [
            'status' => $status,
            'code'   => $this->statusCode,
        ];
        $data   = array_merge($status, $data);

        return $this->respond($data);
    }

    public function failed( $message, $code = BaseResponse::HTTP_BAD_REQUEST, $status = 'error' )
    {
        return $this->status($status, [ 'message' => $message ], $code);
    }

    public function message( $message, $status = 'success' )
    {
        return $this->status($status, [ 'message' => $message ]);
    }

    public function internalError( $message = 'Internal error' )
    {
        return $this->setStatusCode(BaseResponse::HTTP_INTERNAL_SERVER_ERROR)->failed($message);
    }

    public function created( $message = 'created' )
    {
        return $this->setStatusCode(BaseResponse::HTTP_CREATED)->message($message);
    }

    public function success( $data, $status = 'success' )
    {
        return $this->status($status, compact('data'));
    }

    public function notFound( $message = 'not Found' )
    {
        return $this->setStatusCode(BaseResponse::HTTP_NOT_FOUND)->failed($message);
    }
}