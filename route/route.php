<?php
Route::get("/",'user/Login/index');

Route::get('forms',"index/Index/allTable");

Route::get('table/:id','index/Index/showTable');

Route::group('user',function (){
    Route::rule("login",'user/Login/login','get');
    Route::rule('loginVerification','user/Login/loginVerification','post');
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

    //忘记密码
    Route::rule("changepwd",'user/Login/changePwd','get');
    Route::rule("setpassword",'user/Login/setPassword','post');
    //修改密码
    Route::rule('updatepwd','user/Index/updatePwd','get');
    Route::rule('setupdatepwd','user/Index/setUpdatePwd','post');

});
Route::group('announcepar',function (){
    Route::rule('announcepar/:id','user/Announcepar/announcepar','get');
    Route::rule('list','user/Announcepar/dataList','get');
});

Route::group('unit',function (){
    Route::rule("register",'unit/Register/register','get');
    Route::rule("unitreg",'unit/Register/unitReg','post');
    Route::rule("login",'unit/Login/login','get');
    Route::rule("unitloignverifty",'unit/Login/unitLoignVerifty','post');
    Route::rule("index",'unit/Index/index','get') -> middleware('unitLoginCheck');
    Route::rule("baseinfo",'unit/Info/baseInfo','get') -> middleware('unitLoginCheck');
    Route::rule("setbaseinfo",'unit/Info/setBaseInfo','post')->middleware('unitLoginCheck');
    Route::rule("invoiceinfo",'unit/Info/invoiceInfo','get') -> middleware('unitLoginCheck');
    Route::rule("setinvoiceinfo",'unit/Info/setInvoiceInfo','post')->middleware('unitLoginCheck');
    Route::rule("employeelist",'unit/Employee/employeeList','get') -> middleware('unitLoginCheck');
    Route::rule("showadd",'unit/Employee/showAdd','get') -> middleware('unitLoginCheck');
    Route::rule("adduser",'unit/Employee/adduser','post')->middleware('unitLoginCheck');
    Route::rule("gouserinfo/:id",'unit/Employee/userInfo','get')->middleware('unitLoginCheck');
    Route::rule("gosetuserinfo/:id",'unit/Employee/goSetUserInfo','get')->middleware('unitLoginCheck');
    Route::rule("setuserinfo",'unit/Employee/setUserInfo','post')->middleware('unitLoginCheck');
    Route::rule("gosetworkinfo/:id",'unit/Employee/goSetWorkInfo','get')->middleware('unitLoginCheck');
    Route::rule("setwordinfo",'unit/Employee/setWordInfo','post')->middleware('unitLoginCheck');
    Route::rule("gosetinvoiceinfo/:id",'unit/Employee/goSetInvoiceInfo','get')->middleware('unitLoginCheck');
    Route::rule("setinvoiceinfo",'unit/Employee/setInvoiceInfo','post')->middleware('unitLoginCheck');
    //修改密码
    Route::rule('updatepwd','unit/Index/updatePwd','get');
    Route::rule('setupdatepwd','unit/Index/setUpdatePwd','post');
    //报名课程
    Route::rule("signup",'unit/Employee/signUp','get') -> middleware('unitLoginCheck');
    Route::rule("gowrite/:id",'unit/Employee/goWrite','get')->middleware('unitLoginCheck');
    Route::rule("insert",'unit/Employee/insert','post')->middleware('unitLoginCheck');

    Route::rule("gorecording",'unit/Recording/goRecording','get')->middleware('unitLoginCheck');
    Route::rule("gocompleted",'unit/Recording/goCompleted','get')->middleware('unitLoginCheck');
    Route::rule("goundone",'unit/Recording/goUndone','get')->middleware('unitLoginCheck');
    Route::rule("drop",'unit/Index/drop','get');

    Route::rule('null','unit/Index/null','get');
});

Route::group("admin",function (){
    Route::rule("/",'admin/Login/login','get');
    Route::rule("index",'admin/Index/index','get') -> middleware('adminLoginCheck');
    Route::rule("getdata/:tb_name",'admin/Index/getData','get');
    //删除报名信息
    Route::rule("deleteuser","admin/Table/deleteUser",'post');
    Route::rule("admin",'admin/User/show','get');
    Route::rule("drop",'admin/User/drop','get');
    Route::rule("alltable",'admin/Table/allTable','get');
    Route::rule('settablestatus','admin/Table/setTableStatus','post');
    Route::rule('gocreate','admin/Table/goCreate','get');
    Route::rule('addtable','admin/Table/insertTable','post');
    Route::rule('auditstates','admin/Index/auditStates','post');
    //修改表单
    Route::rule('savetable/:id','admin/Table/saveTable','get');
    Route::rule('updatetable','admin/Table/updateTable','post');
    //删除表单
    Route::rule('delete','admin/Table/del','post');

    Route::rule('gorbac','admin/Rbac/goRbac','get');
    Route::rule("setrbac","admin/Rbac/setRbac",'post');
    Route::rule('goadmin','admin/Rbac/goAdmin','get');
    Route::rule("insert",'admin/Rbac/insert','post');
    Route::rule("insert/:id",'admin/Rbac/insert','post');
    Route::rule("getadmininfo/:id",'admin/Rbac/getAdminInfo','post');
    Route::rule("delete/:type",'admin/Rbac/delete','post');
    Route::rule("insertpost","admin/Rbac/insertPost",'post');
    Route::rule("webuser","admin/User/webUser","get");
    //公告
    Route::rule("announce","admin/Announce/announce","get");
    Route::rule("announce_del","admin/Announce/announce_del","get");
    Route::rule("announceedit/:id","admin/Announce/announce_edit","get");
    Route::rule("announceedits","admin/Announce/announce_edit","get");
    Route::rule("anon_del","admin/Announce/anon_del","post");
    Route::rule("anon_addanon","admin/Announce/anon_add","post");
    //个人基本信息设置
    Route::rule("baseinfo","admin/setting.BaseInfo/index","get");
    Route::rule("updatebaseinfo","admin/setting.BaseInfo/updateBaseInfo","post");
    Route::rule("password","admin/setting.BaseInfo/password","get");
    Route::rule("updatepassword","admin/setting.BaseInfo/updatePassword","post");

});


Route::group('excel',function(){
   Route::rule('export','admin/excel.Export/index','get|post');
});

Route::group('layuiedit',function(){
    Route::rule('uploadimg','admin/Layuiedit/uploadImg','post|get');
});

Route::group('pay',function (){
    Route::rule('index','pay/Pay/index','get');
    Route::rule('success','pay/Pay/notice','get');
    Route::rule('/','pay/Pay/pay','get|post');
    Route::rule('notify','pay/Pay/notify','get|post');
    Route::rule('getorder','pay/Pay/getOrder','post');
    Route::rule('jsapipay','pay/Pay/jsApipay','get|post');
    Route::rule('getorderstatus','pay/Pay/getOrderStatus','post');
});
Route::get('qrcodecreate','pay/Qrcodecreate/index');
Route::get('createqrcode','pay/Pay/create');

Route::post('in','admin/Login/in');


