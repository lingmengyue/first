<?php

namespace app\api\model;

use think\Model;

class Theme extends BaseModel
{
    protected $hidden = ['delete_time','topic_img_id','head_img_id','update_time'];
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }

    public function products(){
        //belongsToMany('关联模型','中间表','外键','关联键');
        //详情地址:https://www.kancloud.cn/manual/thinkphp5_1/354060
        //关联模型（必须）：模型名或者模型类名
        //中间表：默认规则是当前模型名+_+关联模型名 （可以指定模型名）
        //外键：中间表的当前模型外键，默认的外键名规则是关联模型名+_id
        //关联键：中间表的当前模型关联键名，默认规则是当前模型名+_id
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProducts($id){
        $theme = self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}
