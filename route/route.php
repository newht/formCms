<?php
Route::get("/",'user/Login/index');

Route::get('forms',"index/Index/allTable");

Route::get('table/:id','index/Index/showTable');

Route::group('user',function (){
    Route::rule('/','user/Login/index','get');
    Route::rule("login",'user/Login/login','get');
    Route::rule("register",'user/Login/register','get');
    Route::rule("index",'user/Index/index','get');
    Route::rule('user','user/Index/user',"get");
    Route::rule('null','user/Index/null','get');
});

Route::group("admin",function (){
    Route::rule("/",'admin/Login/login','get');
    Route::rule("index",'admin/Index/index','get');
    Route::rule("getdata/:tb_name",'admin/Index/getData','get');
    Route::rule("admin",'admin/User/show','get');
    Route::rule("drop",'admin/User/drop','get');
    Route::rule("alltable",'admin/Table/allTable','get');
});
Route::post('in','admin/Login/in');

