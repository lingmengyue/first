<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 20:43
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Cache;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppId,$this->wxAppSecret,$this->code);
    }

    public function get(){
          $result = curl_get($this->wxLoginUrl);
          $wxResult = json_decode($result,true);
          if(empty($wxResult)){
              throw  new Exception('获取session_key及openID时异常');
          }
          else{
              //判断微信返回的信息中是否有错误码
              $loginFail = array_key_exists('errcode',$wxResult);
              if($loginFail){
                  $this->processLoginError($wxResult);
              }
              else{
                return  $this->grantToken($wxResult);
              }
          }
          return $wxResult;
    }

    private function processLoginError($wxResult){
         throw new WeChatException([
             'msg' => $wxResult['errmsg'],
             'errorCode' => $wxResult['errcode'],
         ]);
    }

    /**
     *获取token令牌
     */
    private function grantToken($wxResult){
        //获取openid
        //数据库进行对比，看此openid是否已经存在
        //如果存在，则不处理，如果不存在那么新增一条user记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端里去
        //key 令牌
        //value: wxResult , uid, scope   scope:用户身份
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }
        else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    /**
     *将微信新用户写入user表
     * @openid 微信用户的openid
     */
    private function newUser($openid){
        $user = UserModel::create([
            'openid' => $openid,
        ]);
        return $user->id;
    }

    /**
     *准备缓存的值
     * @wxResult 微信服务器返回来的一组数据
     * @uid 用户在数据库的id值
     */
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;//数值越大权限越高 16代表APP用户权限值，32为CMS用户权限值
        return $cachedValue;
    }

    /**
    *写入缓存
     */
     private function saveToCache($cachedValue){
         $key =self::generateToken();
         //将数组转化为字符串
         $value = json_encode($cachedValue);
         $expire_in = config('setting.token_expire_in');

         //写入缓存
         $request = cache($key, $value, $expire_in);
         if(!$request){
             throw new TokenException([
                 'msg' => '服务器缓存异常',
                 'errorCode' => 10005
             ]);
         }
         return $key;
     }
}