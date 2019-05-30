<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/21
 * Time: 20:00
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;
use think\Collection;

class Order
{
     // 订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    // 真实的商品信息（包括库存量）
    protected $products;
    protected $uid;
    public function place($uid, $oProducts){
        //$oProducts和products作对比
        //products从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }

        //开始创建订单
         $orderSnap = $this->snapOrder($status);
         $order = $this->createOrder($orderSnap);
         $order['pass'] = true;
         return $order;
    }

    //生成订单快照
    private function snapOrder($status){
         $snap = [
             'orderPrice' => 0,
             'totalCount' => 0,
             'pStatus' => [],
             'snapAddress' => '',
             'snapName' => '',
             'snapImg' => ''
         ];
         $snap['orderPrice'] = $status['orderPrice'];
         $snap['totalCount'] = $status['totalCount'];
         $snap['pStatus'] = $status['pStatusArray'];
         $snap['snapAddress'] = json_encode($this->getUserAddress());
         $snap['snapName'] = $this->products[0]['name'];
         $snap['snapImg'] = $this->products[0]['main_img_url'];

         if(count($this->products) > 1){
             $snap['snapName'] .= "等";
         }
         return $snap;
    }

    //创建订单
    private function createOrder($snap){
        Db::startTrans();   //事务开始
        try {
            $orderNum = $this->makeOrderNumber();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNum;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->snap_address = $snap['snapAddress'];
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();  //事务结束
            return [
                'order_no' => $orderNum,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }
        catch (Exception $ex){
            Db::rollback();
            throw $ex;
        }
    }

    //订单编号
    public static function makeOrderNumber(){
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        $orderSn = $yCode[intval(date('Y')) - 2019] . //取首字母，A代表当前年份
                            strtoupper(dechex(date('m'))) .
                            date('d') .
                            substr(microtime(), 2,5) .
                            sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    //获取用户地址
    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => '50001'
            ]);
        }
        return $userAddress->toArray();
    }

    //检查订单库存
    public function checkOrderStock($orderID){
/*        $oProduct = OrderProduct::where('order_id','=',$orderID)->select();
        $this->oProducts = $this->getProductsByOrder($oProduct);
        $status = $this->getOrderStatus();*/
        $oProducts = OrderProduct::where('order_id', '=', $orderID)->select();
        $this->products = $this->getProductsByOrder($oProducts);
        $this->oProducts = $oProducts;
        $status = $this->getOrderStatus();
        return $status;
    }
    //获取订单状态
    private function getOrderStatus(){
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => [] //当前订单中所包含的产品信息
        ];
        foreach ($this->oProducts as $oProductArr){
            $pStatus = $this->getProductsStatus($oProductArr['product_id'],$oProductArr['count'],
                $this->products);
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    //获取产品状态
    private function getProductsStatus($oPID, $oCount, $products){
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0,
        ];
        for($i=0; $i<count($products); $i++ ){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }
        if($pIndex == -1){
            // 客户端传递的product_id有可能根本不存在
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败',
                'errorCode' => '60001'
            ]);
        }
        else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if($product['stock'] - $oCount >= 0){
                $pStatus['haveStock'] = true;
            }
            else{
                throw new OrderException([
                    'msg' => 'id为'.$oPID.'的商品购买件数超过库存,订单创建失败',
                    'errorCode' => '60002'
                ]);
            }
        }
        return $pStatus;
    }

    //根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts){
        $oPIDs = [];
        foreach($oProducts as $item){
            array_push($oPIDs,$item['product_id']);
        }
/*            $user = Product::get(1);
            echo $user->visible(['id','name','email'])->toJson();*/
            $products = ProductModel::all($oPIDs)
                ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
                ->toArray();//一次性获取订单所有商品信息
        return $products;
    }
}