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

class BaseValidate extends Validate
{
     public function goCheck(){
         $request = Request::instance();
         $params = $request->param();
         $result = $this->check($params);
         if(!$result){
             $error =$this->error;
            throw new BaseException($error);
         }
         else{
             return true;
         }
     }

     public function getDateByRule($arrays){
         if(array_key_exists('user_id',$arrays)|| array_key_exists('uid',$arrays)){
             throw new ParameterException([
                 'msg' => '参数中包含有非法的参数名user_id或者uid',
             ]);
         }
         $newArray = [];
         foreach($this->rule as $key => $value){
             $newArray[$key] = $arrays[$key];    //只获取当前模型验证规则中对应的值，如当前模型要验证参数name，只从$array数组中获取参数name的值。防止客户端传过来多余的参数，只获取需要验证的的参数的值
         }
         return $newArray;
     }

    protected function  isPostiveInt($value,$rule="",$data='',$field=''){    //检测是否是整数
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    protected function isNotEmpty($value,$rule = '',$date = '',$field = ''){
         if(empty($value)){
             return false;
         }
         else{
             return true;
         }
    }

    //验证手机号码
    protected function isMobile($value){
         $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';

         $result = preg_match($rule,$value);
         if($result){
             return true;
         }
         else{
             return false;
         }
    }
}