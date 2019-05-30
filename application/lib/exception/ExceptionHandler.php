<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:13
 */

namespace app\lib\exception;

use think\exception\Handle;
use Exception;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //需要返回客户端当前请求的URL路径
    public function render(Exception $e)
    {
        if($e instanceof BaseException){
              $this->code = $e->code;
              $this->msg = $e->msg;
              $this->errorCode = $e->errorCode;
        }
        else{
            if(config('detail_info')){
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = '服务器内部错误,不想告诉你';
                $this->errorCode = 999;
                $this->LogRecord($e);
            }
        }
        $request = Request::instance();//获取所有请求
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' =>$request->url()
        ];
        return json($result,$this->code);
    }

    private function LogRecord(Exception $e){
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
}