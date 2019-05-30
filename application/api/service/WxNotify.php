<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/5/18
 * Time: 21:37
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class WxNotify extends \WxPayNotify
{
    /*<xml>
  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
  <attach><![CDATA[支付测试]]></attach>
  <bank_type><![CDATA[CFT]]></bank_type>
  <fee_type><![CDATA[CNY]]></fee_type>
  <is_subscribe><![CDATA[Y]]></is_subscribe>
  <mch_id><![CDATA[10000100]]></mch_id>
  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
  <result_code><![CDATA[SUCCESS]]></result_code>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
  <time_end><![CDATA[20140903131540]]></time_end>
  <total_fee>1</total_fee>
  <coupon_fee_0><![CDATA[10]]></coupon_fee_0>
  <coupon_count><![CDATA[1]]></coupon_count>
  <coupon_type><![CDATA[CASH]]></coupon_type>
  <coupon_id><![CDATA[10000]]></coupon_id>
  <trade_type><![CDATA[JSAPI]]></trade_type>
  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
</xml>*/
      public function NotifyProcess ($objData,$config,&$msg)
      {

          if($objData['result_code'] == "SUCCESS"){
              $orderNO = $objData['out_trade_no'];
              Db::startTrans();
              try{
                   $order = OrderModel::where('order_no','=',$orderNO)->lock(true)->find();
                   if($order->status == 1){
                       $service = new OrderService();
                       $stockStatus = $service->checkOrderStock($order->id);
                       if($stockStatus['pass']){
                           $this->updateOrderStatus($order->id,true);
                           $this->reduceStock($stockStatus);
                       }
                       else{
                           $this->updateOrderStatus($order->id,false);
                       }
                   }
                   Db::commit();
                   return true;
              }
             catch (Exception $e){
                  Db::rollback();
                  Log::error($e);
                  return false;
             }
          }
          else{
              return true;
          }
      }


      //改变订单状态
      private function updateOrderStatus($orderID,$success){
          $status = $success?OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OF;
          OrderModel::where('id','=',$orderID)->update(['status'=>$status]);
      }

      //减少库存
      private function reduceStock($stockStatus){
          foreach ($stockStatus['pStatusArray'] as $singlePStatus){
              Product::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['count']);
          }
      }
}