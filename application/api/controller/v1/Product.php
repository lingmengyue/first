<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/19
 * Time: 7:51
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductException;
use think\Controller;
use app\api\model\Product as ProductModel;

class Product extends Controller
{
    //获取最近新品
     public function getRecent($count=15){
         (new Count())->goCheck();
         $products = ProductModel::getMostRecent($count);
         if(!$products){
             throw new ProductException();
         }
         //$collection = collection($products)设置一个数据集
         //$products = $collection->hidden(['summary']);隐藏数据集中的summary
         //数据库默认返回数组，在datebase文件中将resultset_type设为collection则默认返回一个数据集
         //$products = $products->hidden(['summary']);
         return $products;
     }

     public function getAllInCategory($id){
         (new IDMustBePostiveInt())->goCheck();
         $products = ProductModel::getProductsByCategoryID($id);
         if(!$products){
             throw new ProductException();
         }
         return $products;
     }

     public function getOne($id){
         (new IDMustBePostiveInt())->goCheck();
         $product = ProductModel::getProductDetail($id);
         if(!$product){
             throw new  ProductException();
         }
         return $product;
     }

     /*获取购物车数据*/
    public function getCart($ids=''){
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ProductModel::getCartInfo($ids);;
        if(!$result){
            throw new ProductException();
        }
        return $result;
    }
     //删除商品接口
     public function deleteOne($id){

     }
}