<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/25
 * Time: 23:23
 */

namespace app\api\model;


class Order extends BaseModel
{
     protected $hidden = ['user_id','delete_time','update_time'];
     protected $autoWriteTimestamp = true;//自动写入时间戳

    public static function getSummaryByUser($uid,$page=1,$size=15){
        $pagingDate = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingDate;
    }

    //构造读取器，自动将order信息里的snap信息格式化
    public function getSnapItemsAttr($value){
          if(!$value){
              return null;
          }
          return json_decode($value);
    }
    public function getSnapAddressAttr($value){
        if(!$value){
            return null;
        }
        return json_decode($value);
    }
}