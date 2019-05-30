<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/18
 * Time: 20:59
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403; //http 状态码
    public $msg = '权限不够，请稍后重试';  //错误具体信息
    public $errorCode = 10000;  //自定义的错误码
}