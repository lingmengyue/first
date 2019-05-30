<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/12
 * Time: 20:14
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\service\Token as TokenServices;
use app\api\validate\AddressNew;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;

class Address extends BaseController
{
    //前置操作
    protected $beforeActionList = [
       'checkPrimaryScope' => ['only' => 'createorupdateaddress,getuseraddress']//此处后面的方法只能小写，前面的却可以大写
    ];
     //创建或修改地址
     public function createOrUpdateAddress(){
         //根据Token来获取用户uid
         //根据uid查找用户数据，判断用户是否存在，如果不存在抛出异常
         //获取用户从客户端提交过来的地址信息
         //根据用户地址信息是否存在，从而判断是添加地址还是更新地址
         $validate = new AddressNew();
         $validate->goCheck();
         $uid = TokenServices::getCurrentUid();
         $user = UserModel::get($uid);
         if(!$user){
             throw new UserException();
         }
         //判断传来的参数中是否存在user_id或uid,以免存入数据库是造成覆盖
         $dataArray = $validate->getDateByRule(input('post.'));
         $userAddress = $user->address;
         if (!$userAddress )
         {
             // 关联属性不存在，则新建
             $user->address()->save($dataArray);
         }
         else
         {
             // 存在则更新
             // fromArrayToModel($user->address, $data);
             // 新增的save方法和更新的save方法并不一样
             // 新增的save来自于关联关系
             // 更新的save来自于模型
             $user->address->save($dataArray);
         }
         return json(new SuccessMessage(),201);
     }

     //获取用户地址
     public function getUserAddress(){
         $uid = TokenServices::getCurrentUid();
         $userAddress = UserAddress::where('user_id',$uid)->find();
         if(!$userAddress){
             throw new UserException([
                 'msg' => '用户地址不存在',
                 'errorCode' => 70001
             ]);
         }
         return $userAddress;
     }
}