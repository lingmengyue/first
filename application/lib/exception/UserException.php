<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 16:49
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = "当前用户不存在";
    public $errorCode = "50000";
}