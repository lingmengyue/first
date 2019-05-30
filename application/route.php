<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/

use think\Route;
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner',[],['id'=>'\d+']);
//http://www.test.com/api/v1/theme?ids=1,2,3
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne',[],['id'=>'\d+']);

//Route::get('api/:version/product/recent','api/:version.Product/getRecent');
//Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
Route::group('api/:version/product',function(){
    Route::get('/recent','api/:version.Product/getRecent');
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
});
Route::get('api/:version/category/all','api/:version.Category/getAllCategory');
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');

Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/get_address','api/:version.Address/getUserAddress');

Route::get('api/:version/memcache','api/:version.Memcache/index');

Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/detail/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');


Route::get('api/wechat/index','api/wechat.Index/index');
Route::post('api/wechat/index','api/wechat.Index/index');
Route::post('api/wechat/message','api/wechat.Index/message');

//小程序回调接口
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify','api/:version.Pay/redirectNotify');

//微信公众号相关链接
Route::group('api/wechat',function(){
    Route::get('/index','api/wechat.Index/index');
    Route::post('/index','api/wechat.Index/index');
    Route::post('/delmenu','api/wechat.Index/delMenu');
    Route::post('/createmenu','api/wechat.Index/diyMenu');
    Route::get('/getmenu','api/wechat.Index/getMenu');
});
