<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/24
 * Time: 13:17
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 404;
    public $msg = "获取用户opeinid错误，请仔细检查code";
    public $errorCode = "100001";

}