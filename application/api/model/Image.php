<?php

namespace app\api\model;

use think\Model;

class Image extends BaseModel
{
    //凡是关联这个Image模型的都只现实url字段，$hidden反之同理
    protected $visible=['url'];
//    获取器命名规则：get + 属性名的驼峰命名+ Attr
//    获取器的作用是对模型实例的（原始）数据做出自动处理。一个获取器对应模型的一个特殊方法（该方法必须为public类型），自动触发不需调用
//    $value的值为返回的数据，$data的值为模型的原始数据，即image表的所有数据
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
}
