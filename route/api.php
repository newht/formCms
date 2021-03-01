<?php

use think\facade\Route;

Route::group('admin', function(){
    // 订单列表
    Route::rule('orderList','admin/Order/order','get');
    Route::rule('orderDel','admin/Order/del','post');

    Route::rule('/test','admin/Order/test','get');
});