<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/8
 * Time: 11:12
 */

namespace app\api\validate;


use app\lib\exception\BaseException;
use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class PagingParameter extends BaseValidate
{
     protected $rule = [
         'page' => 'isPostiveInt',
         'size' => 'isPostiveInt'
     ];

     protected $message = [
         'page' => '分页参数必须为正整数',
         'size' => '分页参数必须为正整数'
     ];
}