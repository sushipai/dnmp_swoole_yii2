<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 下午5:23
 * http,websocket,tcp 服务器配置
 */
 return [
    'server' => [ //ws 服务器
        'server_type' => \One\Swoole\OneServer::SWOOLE_WEBSOCKET_SERVER,
        'port' => 9500,
        'action' =>\App\Server\AppWsServer::class,
        'mode' => SWOOLE_PROCESS,
        'sock_type' => SWOOLE_SOCK_TCP,
        'ip' => '0.0.0.0',
        'set' => [
            'worker_num' => 5
        ]
    ],
    'add_listener' => [
        [  // http 服务器
            'port' => 9501,
            'action' => \App\Server\AppHttpPort::class,
            'type' => SWOOLE_SOCK_TCP,
            'ip' => '0.0.0.0',
            'set' => [
                'open_http_protocol' => true,
                'open_websocket_protocol' => false
            ]
        ],
    ]
];
/**
return [
    'server' => [
        'server_type' => \One\Swoole\OneServer::SWOOLE_HTTP_SERVER,
        'port'        => 9501,
        'action'      => \App\Server\AppHttpServer::class,
        'mode'        => SWOOLE_PROCESS,
        'sock_type'   => SWOOLE_SOCK_TCP,
        'ip'          => '0.0.0.0',
        'set'         => [
//            'worker_num' => 10
        ],
    ]
	
	'server' => [ //ws 服务器
        'server_type' => \One\Swoole\OneServer::SWOOLE_WEBSOCKET_SERVER,
        'port' => 8082,
        'action' => \App\Test\MixPro\Ws::class,
        'mode' => SWOOLE_PROCESS,
        'sock_type' => SWOOLE_SOCK_TCP,
        'ip' => '0.0.0.0',
        'set' => [
            'worker_num' => 5
        ]
    ],
    'add_listener' => [
        [  // http 服务器
            'port' => 9500,
            'action' => \App\Server\AppHttpPort::class,
            'type' => SWOOLE_SOCK_TCP,
            'ip' => '0.0.0.0',
            'set' => [
                'open_http_protocol' => true,
                'open_websocket_protocol' => false
            ]
        ],
    ]
	
];
**/
