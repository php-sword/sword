<?php
namespace App\HttpController;

use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class ExceptionHandler
{
    //控制器错误捕捉
    public static function handle( \Throwable $exception, Request $request, Response $response )
    {
        $debug = config('app.debug',false);

        $message = '500 Internal server error';
        $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);

        $accept = $request->getHeader('accept');
        if($accept and strstr($accept[0], 'html')){
            if($debug){
                $err = nl2br($exception->getMessage()."\n".$exception->getTraceAsString());
                $response->write($err);
            }else{
                $response->write($message);
            }
        }else{
            $response->write(json_encode([
                'status' => 0,
                'code' => 500,
                'message' => $message,
                'result' => [
                    'message' => $exception->getMessage(),
                    'trace'=> $debug? $exception->getTrace(): []
                ]
            ], JSON_UNESCAPED_UNICODE));
        }

    }
}