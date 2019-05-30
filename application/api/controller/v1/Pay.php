<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/28
 * Time: 13:41
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayServiceModel;
use think\Loader;
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay extends BaseController
{
    //前置操作只允许用户访问，CMS管理员不行
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder']
    ];
    //预订单信息
     public function getPreOrder($id=''){
         (new IDMustBePostiveInt())->goCheck();
         $pay = new PayServiceModel($id);
         return $pay->pay();
     }

    //转发微信回调参数
    public function redirectNotify(){
        $config = new \WxPayConfig();
        $notify = new WxNotify();
        $notify->Handle($config);
    }

     //获取微信回调信息
    public function receiveNotify(){
         $xmlData = file_get_contents('php://input');
         $result = curl_post_raw('https://lingmengyue.gicp.net/api/v1/pay/re_notify?XDEBUG_SESSION_START=15204',$xmlData);
    }

}