<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/4/19
 * Time: 21:46
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{

    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }
}