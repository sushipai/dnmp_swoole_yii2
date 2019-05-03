<?php

/**
  http://www.jb51.net/article/87995.htm
  http://blog.csdn.net/daxia_85/article/details/50603587

  GET /user: 逐页列出所有用户
  HEAD /user: 显示用户列表的概要信息
  POST /user: 创建一个新用户
  GET /user/123: 返回用户 123 的详细信息
  HEAD /user/123: 显示用户 123 的概述信息
  PATCH /user/123 and PUT /users/123: 更新用户123
  DELETE /user/123: 删除用户123
  OPTIONS /user: 显示关于末端 /users 支持的动词
  OPTIONS /user/123: 显示有关末端 /users/123 支持的动词
 */
return [
    'enablePrettyUrl' => true, // 启用美化URL
    'enableStrictParsing' => true, // 是否执行严格的url解析
    'showScriptName' => false, // 在URL路径中是否显示脚本入口文件
    'rules' => [
        [
            'class' => 'yii\rest\UrlRule',
            'pluralize' => false, //保证访问列表信息和详情信息时都使用单数形式
            'controller' => [
                'v1/user'
            ],
            'extraPatterns' => [
                'GET guest' => 'guest',
                'GET index' => 'index',
                'POST login' => 'login',
                'POST register' => 'register',
            ]
        ],
    ]
];
