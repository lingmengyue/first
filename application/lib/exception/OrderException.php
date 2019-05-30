<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:16
 */

namespace app\lib\exception;


use think\Exception;

class OrderException extends BaseException
{
     public $code = 404; //http 状态码
     public $msg = '订单中的商品不存在';  //错误具体信息
     public $errorCode = 60000;  //自定义的错误码
}
