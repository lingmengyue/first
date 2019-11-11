<?php
/**
 * Created by PhpStorm.
 * User: 灵梦
 * Date: 2019/11/6
 * Time: 14:51
 */

namespace app\api\controller\v1;


use think\Controller;
use think\Request;
use think\session\driver\Redis;

class Contact extends Controller
{
     public function index(){
         $requestData = Request::instance();
         $data = [
             "name" => $requestData->param('name'),
             "mail" => $requestData->param('email'),
             "from" => $requestData->param('from'),
             "message" => $requestData->param('message'),
         ];
         $comment = new \app\api\model\Message();
         $messageData = $comment->save($data);
         if($messageData){
             return json(['msg' => 'success']);
         }
         else{
             return json(['msg' => 'fail']);
         }
     }

     public function test(){
         $test = new \app\api\model\Test();
         $count = 10000;
         for($i = 2000; $i<=$count; $i++){
             $testArray[$i] =[
                 'name' => "test$i",
                 'ctime' => time(),
                 'operation' => "opr$i"
             ];
         }
         $messageData = $test->saveAll($testArray);
         echo $messageData;
     }

     public function redisTest(){
        $redis = new \think\cache\driver\Redis();
        if($redis->has('test1')){
            return $redis->get('test1');
        }
        else{
            $redis->set('test1','testetstetst',1);
        }

     }
}
