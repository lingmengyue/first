<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 20:28
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\api\service\Token as TokenService;
use app\lib\exception\ParameterException;

class Token
{
     public function getToken($code = ""){
         (new TokenGet())->goCheck();
         $test = new UserToken($code);
         $token = $test->get();
         return [
             'token' => $token,
         ];
     }

    public function verifyToken($token =''){
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
}