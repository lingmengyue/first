<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:16
 */

namespace app\lib\exception;


use think\Exception;

class ParameterException extends Exception
{
     public $code = 400; //http 状态码
     public $msg = '参数错误';  //错误具体信息
     public $errorCode = 10000;  //自定义的错误码
}
