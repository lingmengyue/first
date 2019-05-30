<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/7
 * Time: 9:09
 */

namespace app\api\controller\v1;
use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BannerMissException;
use think\Controller;

class Banner extends Controller
{
   /*
    * 获取指定id的banner信息
    * @url /banner/:id
    * @http GET
    * @id banner的id号
    */
   public function getBanner($id){
/*       $testData = BannerModel::find($id)->toArray();//返回的是一个数据对象
         $testData = BannerModel::find($id);           //返回的是一个模型对象
         print_r($testData);exit;
*/
       (new IDMustBePostiveInt())->goCheck();
/*       $testData = BannerModel::with('items')->find($id);//with用于关联多个模型，以‘,’分隔
       return $testData;*/
       $banner = BannerModel::getBannerByID($id);//返回的是一个模型
       if(!$banner){
           throw new BannerMissException();
       }
       return $banner;
   }
}