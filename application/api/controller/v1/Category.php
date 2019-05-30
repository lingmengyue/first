<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/21
 * Time: 10:12
 */

namespace app\api\controller\v1;

use app\lib\exception\CategoryException;
use think\Controller;
use app\api\model\Category as CategoryModel;
class Category extends Controller
{
     public function getAllCategory(){
         $categorys = CategoryModel::all([],'img');
         if(!$categorys){
             throw new CategoryException();
         }
         return $categorys;
     }
}