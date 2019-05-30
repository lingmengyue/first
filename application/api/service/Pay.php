<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/28
 * Time: 20:54
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Config.php');

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单号不允许为null');
        }
        $this->orderID = $orderID;
    }

    public function pay(){
         //进行库存量检测
        $this->checkOrderVaild();
         $orderService = new OrderService();
         $status = $orderService->checkOrderStock($this->orderID);
         if(!$status['pass']){
             return $status;
         }
        return  $this->makeWxPreOrder($status['orderPrice']);
    }

    //生成微信预订单
    private function makeWxPreOrder($totalPrice){
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderDate = new \WxPayUnifiedOrder();
        $wxOrderDate->SetOut_trade_no($this->orderNO);
        $wxOrderDate->SetTrade_type('JSAPI');
        $wxOrderDate->SetTotal_fee($totalPrice*100);
        $wxOrderDate->SetBody('艾蕾商城');
        $wxOrderDate->SetOpenid($openid);
        $wxOrderDate->SetNotify_url(config('secure.pay_back_url'));
       return $this->getPaySignature($wxOrderDate);
    }

    //获取返回的订单状态
    private function getPaySignature($wxOrderDate){
        $config = new \WxPayConfig();
        $wxOrder = \WxPayApi::unifiedOrder($config,$wxOrderDate);
        if($wxOrder['return_code']!='SUCCESS'||$wxOrder['result_code']!= 'SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取支付预订单失败','error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    //调用支付接口前的参数准备
    private function sign($wxOrder){
        $rand = md5(time().mt_rand(0,1000));                             //生成一个随机字符串
        $jsApipayDate = new \WxPayJsApiPay();
        $jsApipayDate->SetAppid(config('wx.app_id'));                 //获取app_id
        $jsApipayDate->SetTimeStamp((string)time());                         //时间戳生成
        $jsApipayDate->SetNonceStr($rand);                                   //随机字符串
        $jsApipayDate->SetPackage('prepay_id='.$wxOrder['prepay_id']);//统一下单接口返回的 prepay_id 参数值
        $jsApipayDate->SetSignType('md5');                            //签名算法类型
        $sign = $jsApipayDate->MakeSign();                                   //签名生成
        $rawValues = $jsApipayDate->GetValues();
        $rawValues['paysign'] = $sign;
        unset($rawValues['appID']);
        return $rawValues;

    }

    //处理预订单id
    private function recordPreOrder($wxOrder){
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }

    //检查当前订单操作是否合法
    private function checkOrderVaild(){
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if(!$order){
            throw new OrderException();
        }
        if(!TokenService::isValidOperate($order->user_id)){
                throw new TokenException([
                    'msg' => '订单与当前用户不匹配',
                    'errorCode' => '10003'
                ]);
        }
        if($order->status != OrderStatusEnum::UNPAID){
              throw new OrderException([
                  'msg' => '订单状态异常',
                  'errorCode' => '60004',
                  'code' => 400
              ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }
}