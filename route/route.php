<?php
Route::get("/",'user/Login/index');

Route::get('forms',"index/Index/allTable");

Route::get('table/:id','index/Index/showTable');

Route::group('user',function (){
    Route::rule("login",'user/Login/login','get');
    Route::rule('loginVerification','user/Login/loginVerification','post');
    Route::rule("unitlogin",'user/Login/unitIndex','get');
    Route::rule("unitloignverifty",'user/Login/unitLoignVerifty','post');
    Route::rule("unitregister",'user/Register/unitRegister','get');
    Route::rule("unitreg","user/Register/unitReg",'post');
    Route::rule("register",'user/Register/register','get');
    Route::rule("registerVerification",'user/Register/registerVerification','post');
    Route::rule("index",'user/Index/index','get') -> middleware('loginCheck');
    Route::rule("drop",'user/Index/drop','get')-> middleware('loginCheck');
    Route::rule("userinfo",'user/Index/goInfo','get') -> middleware('loginCheck');
    Route::rule("setuserinfo",'user/Info/setUserInfo','post')->middleware('loginCheck');
    Route::rule("workinfo",'user/Index/goWorkInfo','get') -> middleware('loginCheck');
    Route::rule("setemployerinfo",'user/Info/setEmployerInfo','post')->middleware('loginCheck');
    Route::rule("signup",'user/Index/signUp','get') -> middleware('loginCheck');
    Route::rule("gowrite/:id",'user/Forms/goWrite','get')->middleware('loginCheck');
    Route::rule("insert",'user/Forms/insert','post')->middleware('loginCheck');
    Route::rule("gorecording",'user/Recording/goRecording','get')->middleware('loginCheck');

    Route::rule('null','user/Index/null','get');
});

Route::group("admin",function (){
    Route::rule("/",'admin/Login/login','get');
    Route::rule("index",'admin/Index/index','get');
    Route::rule("getdata/:tb_name",'admin/Index/getData','get');
    Route::rule("admin",'admin/User/show','get');
    Route::rule("drop",'admin/User/drop','get');
    Route::rule("alltable",'admin/Table/allTable','get');
    Route::rule('gocreate','admin/Table/goCreate','get');
    Route::rule('addtable','admin/Table/insertTable','post');
    Route::rule('auditstates','admin/Index/auditStates','post');
    Route::rule('gorbac','admin/Rbac/goRbac','get');
    Route::rule("setrbac","admin/Rbac/setRbac",'post');
    Route::rule('goadmin','admin/Rbac/goAdmin','get');
    Route::rule("insert",'admin/Rbac/insert','post');
    Route::rule("insert/:id",'admin/Rbac/insert','post');
    Route::rule("getadmininfo/:id",'admin/Rbac/getAdminInfo','post');
    Route::rule("delete/:type",'admin/Rbac/delete','post');
    Route::rule("insertpost","admin/Rbac/insertPost",'post');
    Route::rule("webuser","admin/User/webUser","get");
});
Route::post('in','admin/Login/in');


