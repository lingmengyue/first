<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected function prefixImgUrl($value,$data){
        $finalUrl=$value;
        if($data['from']==1){
            $finalUrl = config('setting.img_prefix').$value;//添加域名前缀
        }
        return $finalUrl;
    }
}
