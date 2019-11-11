<?php
/**
 * Created by PhpStorm.
 * User: 灵梦
 * Date: 2019/11/11
 * Time: 14:51
 */

namespace app\api\controller\v1;


use app\api\controller\mail\PhpMail;
use think\Controller;
class Mail extends Controller
{

    public function sendMail(){
        $content = "这是一封小小的邮件2,请收下";
        $mail = new PhpMail();
        $data = $mail->sendMail($content);
        return $data;
    }

}
