<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/19
 * Time: 23:39
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = "请求的商品不存在";
    public $errorCode = "20000";
}