<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 20:42
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }
     public static function getByOpenID($openid){
         //查询user表中是否存在有当前openid的数据
         $user = self::where('openid','=',$openid)->find();
         return $user;
     }
}