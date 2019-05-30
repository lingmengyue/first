<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/9
 * Time: 21:07
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;


class Token
{
    //生成Token
    public static function generateToken(){
        $randChars = getRandChar(32);
        //访问时间戳
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('secure.token_salt');
        //对这三组数组进行md5加密
        return md5($randChars.$timestamp.$salt);
    }

    //获取当前用户Token值
    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }
        else{
            if(!is_array($vars)){     //将值转换为数组，true为数组，false为对象
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('尝试获取的token变量并不存在');
            }
        }
    }

    //获取当前用户id
    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    //权限值大于等于用户才允许访问
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope >= ScopeEnum::User){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //只有用户才有权限访问order
    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope == ScopeEnum::User){
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    //检测操作是否合法
    public static function isValidOperate($checkUID){
        if(!$checkUID){
            throw new Exception('检测到当前传来的uid参数为空');
        }
        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID == $checkUID){
            return true;
        }
        return false;
    }

    //检验token是否失效
    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }
}