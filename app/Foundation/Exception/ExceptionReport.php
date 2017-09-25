<?php

/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 20/9/2017
 * Time: 6:03 PM
 */

namespace App\Foundation\Exception;

use App\Foundation\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ExceptionReport
{
    use ApiResponse;

    public $exception;

    public $request;

    protected $report;

    function __construct( Request $request, \Exception $exception )
    {
        $this->request = $request;

        $this->exception = $exception;

    }

    public $doReport
        = [
            AuthenticationException::class => [ '未授权', 401 ],
            ModelNotFoundException::class  => [ '该模型没有找到', 404 ],
        ];

    public function shouldReturn()
    {

        if (!($this->request->wantsJson() || $this->request->ajax())) {
            return false;
        }

        foreach ( array_keys($this->doReport) as $report ) {

            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    public static function make( \Exception $e )
    {
        return new static(request(), $e);
    }

    public function report()
    {
        $message = $this->doReport[ $this->report ];

        return $this->failed($message[ 0 ], $message[ 1 ]);

    }
}