<?php

namespace app\api\model;

class Product extends BaseModel
{
    protected $hidden = ['delete_time','from','create_time','update_time','pivot'];
    public function getMainImgUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }

    //最近新品接口
    public static function getMostRecent($count){
        $products = self::limit($count)->order('create_time desc')->select();
        return $products;
    }

    //分类所属商品接口
    public static function getProductsByCategoryID($categoryID){
        $products = self::where('category_id','=',$categoryID)->select();
        $productsData = $products->hidden(['summary','img_id','category_id'])->toArray();
        return $productsData;
    }

    //产品图片
    public function imgs(){
      return  $this->hasMany('ProductImage','product_id','id');
    }

    //产品参数
    public function properties(){
       return $this->hasMany('ProductProperty','product_id','id');
    }

    //获取产品详情的所有信息
    public static function getProductDetail($id){
/*        $product = self::with(['imgs','properties'])->find($id);*/
        $product = self::with(
            [
                'imgs' => function ($query)
                {
                    //查询构造器
                    $query->with(['imgUrl'])
                        ->order('order', 'asc');
                }])
            ->with('properties')
            ->select($id);
        return $product;
    }

    //根据前端购物车获取到的商品id查询对应商品简略信息并返回
    public static function getCartInfo($ids){
        /*        $product = self::with(['imgs','properties'])->find($id);*/
        $cartData = self::select($ids);
        return $cartData;
    }
}

