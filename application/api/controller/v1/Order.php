<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/19
 * Time: 18:30
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Controller;
use app\api\service\Token as TokenServices;

class Order extends BaseController
{
    // 用户在选择商品后，向API提交包含它选择商品的相关信息
    // API在接收到信息后，需要检查订单相关商品的库存量
    // 有库存，把订单数据存入数据库中。下单成功了，返回客户端信息，告诉客户端可以支付了
    // 调用支付接口，进行支付
    // 再次对库存量进行检测
    // 服务器这边就可以调用微信的支付接口进行支付
    //小程序根据服务器返回的结果拉起微信支付
    // 微信会返回给我们一个支付的结果（异步的）
    // 支付成功：进行库存量的检查
    // 成功：进行库存量的扣除

    //前置操作,检测访问权限
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeorder'],//此处后面的方法只能小写，前面的却可以大写
        'checkPrimaryScope' => ['only' => 'getdetail,getsummarybyuser']//此处后面的方法只能小写，前面的却可以大写
    ];
    //订单接口
    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');//获取数组参数
        $uid = TokenServices::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }

    //获取历史订单接口
    public function getSummaryByUser($page=1,$size=15){
        (new PagingParameter())->goCheck();
        $uid = TokenServices::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pagingOrders->currentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->currentPage()
        ];
    }

    //订单详情接口
    public function getDetail($id){
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }
}