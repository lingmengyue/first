<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:16
 */

namespace app\lib\exception;


use think\Exception;

class SuccessMessage
{
     public $code = 201; //http 状态码
     public $msg = 'ok';  //错误具体信息
     public $errorCode = 0;  //自定义的错误码
}
