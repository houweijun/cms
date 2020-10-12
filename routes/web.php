<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//设置默认访问路由
Route::group(['namespace' => 'Admin\Rlogin'], function () {
    Route::get('/', 'RloginController@login');
});

//后端
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //后台登录
    Route::group(['namespace' => 'Rlogin'], function () {
        Route::any('login', 'RloginController@login');
    });

    //后台登录
    Route::group(['middleware' => 'login'], function () {

        //登录后台
        Route::group(['namespace' => 'Rlogin'], function () {
            //后台首页
            Route::any('index', 'RloginController@index');
            //退出登录
            Route::any('logout', 'RloginController@logout');
        });

        //系统管理
        Route::group(['prefix' => 'system', 'namespace' => 'System'], function () {
            //参数模块
            Route::any('parameter/index', 'ParameterController@index');
            Route::any('parameter/add', 'ParameterController@parameterAdd');
            Route::any('parameter/edit/{id}', 'ParameterController@parameterEdit');

            //修改个人密码
            Route::any('repassword/{id}', 'UserController@repassword');

            //新菜单模块
            Route::any('newmenu/index', 'NewMenuController@index');
            Route::any('newmenu/add/{parentId?}', 'NewMenuController@add');
            Route::any('newmenu/edit/{id}', 'NewMenuController@edit');
            Route::any('newmenu/del', 'NewMenuController@delete');
            Route::any('newmenu/order', 'NewMenuController@order');


            //渠道模块
            Route::any('org/index', 'OrgController@index');
            Route::any('org/add/{parentId?}', 'OrgController@orgAdd');
            Route::any('org/edit/{id}', 'OrgController@orgEdit');
            Route::any('org/del', 'OrgController@orgDel');
            Route::any('org/order', 'OrgController@order');

            //日志模块
            Route::any('log/index', 'LogController@index');
            Route::any('log/del/{id}', 'LogController@logDel');

            //管理员管理
            Route::any('adminuser/index', 'AdminUserController@index');
            //管理员添加
            Route::any('adminuser/add', 'AdminUserController@add');
            //管理员编辑
            Route::any('adminuser/edit/{id}', 'AdminUserController@edit');
            //管理员状态更新
            Route::get('adminuser/status/{id}/{status}', 'AdminUserController@status');
            //ajax删除管理员
            Route::any('adminuser/del', 'AdminUserController@delete');

            //权限管理
            Route::any('authority/index', 'AuthorityController@index');
            //权限添加
            Route::any('authority/add', 'AuthorityController@add');
            //权限编辑
            Route::any('authority/edit/{id}', 'AuthorityController@edit');
            //ajax删除权限
            Route::any('authority/del', 'AuthorityController@delete');

        });

        //文章分类管理
        Route::group(['prefix' => 'portal', 'namespace' => 'Portal'], function () {

          //文章分类管理
          Route::group(['prefix' => 'category'], function () {
            //文章分类首页
            Route::any('index', 'CategoryController@index');
            //文章分类添加
            Route::any('add/{parentId?}', 'CategoryController@add');
            //文章分类修改
            Route::any('edit/{id}', 'CategoryController@edit');
            //文章分类删除
            Route::any('del', 'CategoryController@delete');
            //文章分类排序
            Route::any('order', 'CategoryController@order');
          });

        });

        //后台上传资源
        Route::group(['prefix' => 'asset', 'namespace' => 'Asset'], function () {
            //webuploader上传资源
            Route::get('webuploader', 'webUpLoaderController@index');
            //文件上传处理
            Route::any('upload', 'webUpLoaderController@upload');
            //文件上传取消
            Route::any('cancel', 'webUpLoaderController@cancel');
            //导入上传文件
            Route::any('lead', 'webUpLoaderController@lead');
        });

    });
});

//前端接口
Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => ['CheckApi', 'throttle:60,1']], function () {
    //获取token
    Route::post('getcode/token', 'GetCodeController@token');
    //获取礼包
    Route::post('getcode/show', 'GetCodeController@show');
    //获取激活码详情
    Route::post('getcode/details', 'GetCodeController@details');
    //获取激活码页面
    Route::post('getcode/page', 'GetCodeController@page');
});

//测试
Route::any('test/index', 'Api\TestController@index');