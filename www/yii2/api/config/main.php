<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [
            'basePath' => '@app/modules/v2',
            'class' => 'api\modules\v2\Module'
        ]
    ],
    'components' => [
        'errorHandler' => [
            'class' => 'api\extensions\ApiErrorHandler',
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false, // 显示一个HTTP 403 错误而不是跳转到登录界面
            'loginUrl' => null,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->statusCode = 200;
                    $data = $response->data;
                    $response->data = [
                        'success' => isset($data['success']) ? $data['success'] : false,
                        'message' => isset($data['message']) ? $data['message'] : null,
                        'data' => isset($data['data']) ? $data['data'] : null,
                    ];
                }
            },
        ],
        'request' => [
            'class' => 'yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => require(__DIR__ . '/rules.php'),
    ],
    'params' => $params,
];
