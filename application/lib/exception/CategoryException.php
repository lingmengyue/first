<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 15:37
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 400; //http 状态码
    public $msg = '指定的类目不存在，请仔细检查参数';  //错误具体信息
    public $errorCode = 40000;  //自定义的错误码
}