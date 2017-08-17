<?php

Route::group(['middleware' => ['web'],'namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::auth();

    Route::get('login',['as' => 'admin.login','uses' => 'Auth\AuthController@showLoginForm']);
    Route::get('home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
    Route::resource('admin_user', 'AdminUserController');
    Route::post('admin_user/destroyall',['as'=>'admin.admin_user.destroy.all','uses'=>'AdminUserController@destroyAll']);
    Route::resource('role', 'RoleController');
    Route::post('role/destroyall',['as'=>'admin.role.destroy.all','uses'=>'RoleController@destroyAll']);
    Route::get('role/{id}/permissions',['as'=>'admin.role.permissions','uses'=>'RoleController@permissions']);
    Route::post('role/{id}/permissions',['as'=>'admin.role.permissions','uses'=>'RoleController@storePermissions']);
    Route::resource('permission', 'PermissionController');
    Route::post('permission/destroyall',['as'=>'admin.permission.destroy.all','uses'=>'PermissionController@destroyAll']);
    Route::resource('blog', 'BlogController');

    //TODO:linglu
    Route::resource('user_credit',          'UserCreditController');      //授信管理
    Route::post('userCredit/creditStatus',      ['as'=>'admin.user_credit.creditStatus','uses'=>'UserCreditController@creditStatus']);
    Route::get('userCredit/creditDetail/{uid}', ['as'=>'admin.user_credit.creditDetail','uses'=>'UserCreditController@creditDetail']);

    //借款记录
    Route::resource('user_loan',    'UserLoanController');
    Route::get('userLoan/loanRecord/',['as'=>'admin.user_loan.loanrecord','uses'=>'UserLoanController@loanRecord']);
    Route::get('userLoan/waitRecord/',['as'=>'admin.user_loan.waitrecord','uses'=>'UserLoanController@waitRecord']);
    Route::get('userLoan/alreadyRecord/',['as'=>'admin.user_loan.alreadyrecord','uses'=>'UserLoanController@alreadyRecord']);
    //借款审核操作
    Route::get('userLoan/loanCheck',    ['as'=>'admin.user_loan.loanCheck','uses'=>'UserLoanController@loanCheck']);
    //借款放款操作
    Route::post('userLoan/loanOperate', ['as'=>'admin.user_loan.loanOperate','uses'=>'UserLoanController@loanOperate']);

    //借款详情
    Route::get('userLoan/loanDetail/{id}',['as'=>'admin.user_loan.loanDetail',  'uses'=>'UserLoanController@loanDetail']);

    Route::resource('user_refund',  'UserRefundController');
});


Route::get('/admin', function () {
    return view('admin.welcome');
});

Route::get('/uploads/{path}', 'Oss\OssController@uploadsPath',function($path){})->where('path', '[\w\/\.-]*');

Route::get('/',function(){
    return redirect('/app');
});

Route::group(['middleware' => ['web'], 'namespace' => 'Wap', 'prefix' => 'app'], function () {

    Route::get('/','User\HomeController@index');

    //注册协议
    Route::get('/register_deal', 'User\HomeController@registerDeal');
    //服务协议
    Route::get('/server_deal', 'User\HomeController@serverDeal');
    //借款协议
    Route::get('/loan_deal', 'User\HomeController@loanDeal');

    //证件照片上传
    Route::any('/do_upload','User\HomeController@doUpload');

    //用户注册
    Route::get('/register', 'User\RegisterController@index');
    Route::post('/do_register','User\RegisterController@doRegister');
    Route::post('/register/send_verify','User\RegisterController@sendSms');
    //用户登录
    Route::get('/login','User\LoginController@index');
    Route::post('/do_login','User\LoginController@doLogin');
    //用户退出
    Route::get('/logout','User\LoginController@loginOut');

    //授信基础信息
    Route::get('/credit/step/1','Credit\CreditController@creditOne');
    Route::post('/do_credit/step/1','Credit\CreditController@doCreditOne');

    //通信录信息
    Route::get('/credit/step/2','Credit\CreditController@creditTwo');
    Route::post('/do_credit/step/2','Credit\CreditController@doCreditTwo');

    //联系人信息
    Route::get('/credit/step/3','Credit\CreditController@creditThree');
    Route::post('/do_credit/step/3','Credit\CreditController@doCreditThree');

    //证件照信息
    Route::get('/credit/step/4','Credit\CreditController@creditFour');
    Route::post('/do_credit/step/4','Credit\CreditController@doCreditFour');
    Route::post('/do_credit/step/save_photo','User\HomeController@savePhoto');

    //审核成功页
    Route::get('/credit/step/5','Credit\CreditController@creditFive');
    Route::post('/do_credit/step/5','Credit\CreditController@doCreditFive');

    //绑卡
    Route::get('/bind/bankcard','Borrow\BankcardController@bankCard');
    Route::post('/bind/get_verify','Borrow\BankcardController@getVerifyCode');
    Route::post('/bind/do_bankcard','Borrow\BankcardController@doBindBankCard');

    //借款
    Route::get('/borrow/apply','Borrow\BorrowController@apply');
    Route::post('/borrow/do_apply','Borrow\BorrowController@doApply');
    Route::get('/borrow/detail/{id}','Borrow\BorrowController@detail');
    Route::get('/borrow/repayment/{id}','Borrow\BorrowController@repayment');
    Route::post('/borrow/do_repayment','Borrow\BorrowController@doRepayment');
    Route::get('/borrow/repayment/success','Borrow\BorrowController@repaymentSuccess');
    Route::get('/borrow/run','Borrow\AutoController@borrowInterestRun');

    //ios审核type
    Route::post('/api/get_type','AppController@getShowType');

});

Route::group(['middleware' => ['appApiMustLogin','web'],'namespace' => 'AppApi', 'prefix' => 'app'], function () {

    //face++
    Route::post('/api/face_id','FaceIdController@getFacePic');
    //通讯录
    Route::post('/api/phone_book','FaceIdController@getPhoneBook');
    //短信
    Route::post('/api/sms','FaceIdController@getSms');
    //通话记录
    Route::post('/api/history','FaceIdController@getHistory');
    //图片上传
    Route::post('/api/uploadPic','FaceIdController@uploadPic');

});

