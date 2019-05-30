<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/3/25
 * Time: 19:03
 */

namespace app\api\controller\v1;
use \think\cache\driver\Memcache as Memcaches;
use think\Controller;
use app\api\model\Product as ProductModel;


class Memcache extends Controller
{
    public function index(){
        $mem = new Memcaches();
        $data =  new ProductModel();
        $testdate = $data->select()->toArray();
        $mem->set('test1',$testdate,time()+100);
        $mem->clear();
        print_r($mem->get('test1'));
    }

}