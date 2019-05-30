<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/11
 * Time: 23:57
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
      protected $hidden = ['img_id','delete_time','product_id'];

      public function imgUrl(){
          return $this->belongsTo('Image','img_id','id');
      }
}