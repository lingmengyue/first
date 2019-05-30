<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/12
 * Time: 20:22
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
       protected $rule = [
           'name' => 'require|isNotEmpty',
           'mobile' => 'require|isMobile',
           'province' => 'require|isNotEmpty',
           'city' => 'require|isNotEmpty',
           'country' => 'require|isNotEmpty',
           'detail' => 'require|isNotEmpty',
       ];
}