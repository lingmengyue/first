<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/21
 * Time: 12:15
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{

    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected $singRule = [
        'product_id' => 'require|isPostiveInt',
        'count' => 'require|isPostiveInt',
    ];

    protected function checkProducts($values){
        if(!is_array($values)){
            throw new ParameterException([
                'msg' => '商品参数错误'
            ]);
        }
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value){
        $validate = new BaseValidate($this->singRule);//$this->singRule代表参数rules
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
    }
}