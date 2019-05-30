<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 9:18
 */

namespace app\api\model;

use think\Db;
use think\Model;

class Banner extends BaseModel
{
//    隐藏特定字段
    protected $hidden=['delete_time','update_time'];
//    只显示特定字段
//     protected $visible
    public function items(){
        //参数顺序：被关联的模型，被关联模型外键，当前模型主键
        return $this->hasMany("BannerItem",'banner_id','id');
    }
    public static function getBannerByID($id){
          $banner = self::with(['items','items.img'])->find($id);
          return $banner;
    }
}