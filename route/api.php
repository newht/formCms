<?php

use think\facade\Route;

Route::group('admin', function(){
    // 订单列表
    Route::rule('orderList','admin/Order/order','get');
    // 订单删除
    Route::rule('orderDel','admin/Order/del','post');
    // 后台首页信息页面
    Route::get('info','admin/Index/info');

});

Route::group('user', function(){
    // 获取手机验证码
    Route::post('code','user/Login/code');
    // 设置新密码
    Route::post('setNewPwd','user/Login/setNewPwd');
    // 单位忘记密码
    Route::rule("changeUnitPwd",'user/Login/changeUnitPwd','get');

});

// 推广模块
Route::group('promotion', function(){
    // 推广界面
    Route::get('index','promotion/Index/index');
    // 我的推广
    Route::get('my','promotion/Index/my');
    Route::get('test','promotion/Index/test');
});