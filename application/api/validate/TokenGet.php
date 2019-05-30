<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 20:29
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
      protected $rule = [
          'code' => 'require|isNotEmpty'
      ];

      protected $message = [
          'code' => '未接收到code，请仔细检查'
      ];

}