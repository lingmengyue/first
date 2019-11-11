<?php
/**
 * Created by PhpStorm.
 * User: 灵梦
 * Date: 2019/9/29
 * Time: 14:48
 */

namespace app\api\model;


use think\Model;


class Comment extends Model
{
    protected $autoWriteTimestamp = true;
    public function getCreateTimeAttr($value){
        return $value*1000;//tp只支持秒级时间写入，vue进行时间转换时需要毫秒级的精度，此处做了秒转毫秒的步骤
    }
}