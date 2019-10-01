<?php
/**
 * Created by PhpStorm.
 * User: 灵梦
 * Date: 2019/9/29
 * Time: 22:18
 */

namespace app\api\controller;


use think\Controller;
class Index extends Controller
{
    public function index(){
        return $this->fetch();
    }

}