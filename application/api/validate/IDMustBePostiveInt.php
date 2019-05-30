<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 15:50
 */

namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate
{
   protected $rule = [
       'id' => 'require|isPostiveInt',
   ];

   protected $message = [
       'id' => 'id必须为正整数',
   ];

}