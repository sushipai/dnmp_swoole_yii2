<?php

return [
    'SERVER_NAME' => 'EasySwoole',
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER,
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'max_request' => 5000,
            'task_worker_num' => 8,
            'task_max_request' => 1000,
            'enable_static_handler' => true,
            'document_root' => EASYSWOOLE_ROOT . '/public',
        ],
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'CONSOLE' => [
        'ENABLE' => true,
        'LISTEN_ADDRESS' => '127.0.0.1',
        'HOST' => '127.0.0.1',
        'PORT' => 9500,
        'EXPIRE' => '120',
        'AUTH' => [
            [
                'USER' => 'root',
                'PASSWORD' => 'root',
                'MODULES' => [
                    'auth', 'server', 'help', 'test'
                ],
                'PUSH_LOG' => true
            ]
        ]
    ],
    /* ################ MYSQL CONFIG ################## */
    'MYSQL' => [
        'host' => 'mysql',
        'port' => '3306',
        'user' => 'root',
        'password' => 'root',
        'timeout' => '5',
        'charset' => 'utf8mb4',
        'database' => 'cry',
        'POOL_MAX_NUM' => '20',
        'POOL_TIME_OUT' => '0.1',
    ],
    /* ################ REDIS CONFIG ################## */
    'REDIS' => [
        'host' => 'redis',
        'port' => '6379',
        'auth' => '',
        'POOL_MAX_NUM' => '20',
        'POOL_MIN_NUM' => '5',
        'POOL_TIME_OUT' => '0.1',
    ],
    'FAST_CACHE' => [
        'PROCESS_NUM' => 5
    ]
];
