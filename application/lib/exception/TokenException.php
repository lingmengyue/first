<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/10
 * Time: 0:06
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
      public $code = 401;
      public $msg = 'Token已经过期或者Token无效';
      public $errorCode = 10001;
}