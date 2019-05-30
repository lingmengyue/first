<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/5/7
 * Time: 18:56
 */

namespace app\api\controller\wechat;


use think\Controller;
use EasyWeChat\Factory;
use think\Request;

class Index extends Controller
{
    private $config = [
        'app_id' => 'wx13d0dbacc51e565b',
        'secret' => 'e36760ab3a97433983693c1f813230bd',
        'token' => 'fole',
        'response_type' => 'array',
        'log' => [
            'level'      => 'debug',
            'permission' =>  0777,
            'file'       =>  LOG_PATH.'easywechat.log',
        ],
      ];

    public function fa(){
        $app = Factory::officialAccount($this->config);
        return $app;
    }

      public function index(){
          $app = Factory::officialAccount($this->config);
          $app->server->push(function ($message) {
    /*          return "复读机,你输入的话为：".$message['Content'];*/
              switch ($message['MsgType']) {
                  case 'event':
                      return '收到事件消息';
                      break;
                  case 'text':
                      return '收到文本消息';
                      break;
                  case 'image':
                      return '收到图片消息';
                      break;
                  case 'voice':
                      return '收到语音消息';
                      break;
                  case 'video':
                      return '收到视频消息';
                      break;
                  case 'location':
                      return '收到坐标消息';
                      break;
                  case 'link':
                      return '收到链接消息';
                      break;
                  case 'file':
                      return '收到文件消息';
                  // ... 其它消息
                  default:
                      return '收到其它消息';
                      break;
              }

          });
          $response = $app->server->serve();
          $response->send();exit;
      }

      public function diyMenu(){
          $app = Factory::officialAccount($this->config);
          $buttons = [
              [
                  "type" => "click",
                  "name" => "摸鱼达人2",
                  "key"  => "V1001_TODAY_MUSIC"
              ],
              [
                  "name"       => "自定义菜单",
                  "sub_button" => [
                      [
                          "type" => "view",
                          "name" => "搜索",
                          "url"  => "http://www.soso.com/"
                      ],
                      [
                          "type" => "view",
                          "name" => "视频",
                          "url"  => "http://v.qq.com/"
                      ],
                      [
                          "type" => "click",
                          "name" => "赞一下我们",
                          "key" => "V1001_GOOD"
                      ],
                  ],
              ],
          ];
        return  $app->menu->create($buttons);
      }

      public function getMenu(){
          $app = Factory::officialAccount($this->config);
          $current = $app->menu->list();
//          $current = $app->menu->current();
          return $current;
      }

      public function delMenu(){
          $app = Factory::officialAccount($this->config);
          $app->menu->delete();
      }
}