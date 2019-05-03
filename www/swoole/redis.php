<?php

//$http = new swoole_http_server('0.0.0.0',9501);
//$http->on('request', function ($request, $response) {
//	go(function() {
//	    $redis = new Swoole\Coroutine\Redis();
//	    $redis->connect('127.0.0.1',6379);
//	    $value = $redis->get($request->get['key']);
//	    $response->end($value);
//    });
//});
//$http->start();

$redis = new Swoole\Coroutine\Redis();
$redis->connect('127.0.0.1', 6379, function(swoole_redis $redis, $result) {
    if ($result) {
        echo "连接成功" . PHP_EOL;
        $key = 'time';
        $redis->set($key, time(), function(swoole_redis $redis, $result) {
            var_dump($result);
        });
        $redis->get($key, function (swoole_redis $redis, $result) {
            var_dump($result);
            $redis->close();
        });
    } else {
        echo "连接失败" . PHP_EOL;
    }
});

echo "异步redis" . PHP_EOL;
