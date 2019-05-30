<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 10:17
 */

namespace app\api\model;


class Category extends BaseModel
{
     protected $hidden = ['delete_time','update_time'];

     public function img(){
         return $this->belongsTo('Image','topic_img_id','id');
     }
}