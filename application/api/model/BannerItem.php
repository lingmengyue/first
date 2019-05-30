<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 17:08
 */

namespace app\api\model;


use think\Model;

class BannerItem extends BaseModel
{
    protected $hidden=['delete_time','id','img_id','update_time','banner_id'];
      public function img(){
          return $this->belongsTo('Image','img_id','id');
      }
}