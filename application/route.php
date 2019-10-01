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



use think\Route;
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner',[],['id'=>'\d+']);
//http://www.test.com/api/v1/theme?ids=1,2,3
Route::get('api/:version/theme','api/:version.Theme/getSimpleList'); //获取所有专题简略信息  www.lingmeng.com/api/v1/theme?ids=1,2,3
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
Route::get('api/:version/test','api/:version.Test/index');


//vue测试
Route::group('api/vue',function(){
    Route::get('/get','api/v1.Vue/getInfo');
    Route::get('/jsonp','api/v1.Vue/jsonpInfo');
    Route::get('/delete','api/v1.Vue/deleteInfo');
    Route::get('/newslist','api/v1.Vue/getNewsList');
    Route::get('/newsDetail/:id','api/v1.Vue/getNewsDetail');
    Route::get('/comment/:limit/:page','api/v1.Vue/getComment');
    Route::get('/imageCategory','api/v1.Vue/getImageCategory');
    Route::get('/imageCategoryContent/:id','api/v1.Vue/getImageCategoryContent');
    Route::get('/imageContent/:id','api/v1.Vue/getImageContent');
    Route::post('/insert','api/v1.Vue/insertInfo');
    Route::post('/post','api/v1.Vue/postInfo');
    Route::post('/saveComment','api/v1.Vue/saveComment');
});

