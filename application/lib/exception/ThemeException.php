<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/18
 * Time: 11:28
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = "指定的主题不存在，请检查主题ID";
    public $errorCode = "30000";
}