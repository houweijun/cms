<?php

return [
//游戏门户参数
    'portal'=>[
        //注册开启验证码
        'register_captcha'=>true,
        //登陆开启验证码
        'login_captcha'=>true,
    ],
    //运营门户参数
    'admin'=>[
        //登陆开启验证码
        'login_captcha'=>true,
        'security_key'=>'vastlee@123',
        'test_string'=>'doit'
    ],

];
