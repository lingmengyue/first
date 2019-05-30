<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/19
 * Time: 22:23
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    //限定最新商品最多展示15个，最少1个
     protected $rule = [
         'count' => 'isPostiveInt|between:1,15'
     ];
    protected $message = [
        'count' => 'count规则错误,count需为1~15之间的正整数',
    ];
}