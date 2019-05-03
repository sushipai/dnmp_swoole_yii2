<?php
class Ws {
    CONST HOST = "0.0.0.0";
    CONST PORT = 9501;

    public $ws = null;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->set(
            [
                'worker_num' => 2,
            ]
        );

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);

        $this->ws->start();
    }


    /**
     * 监听连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request)
    {
        echo "建立连接，客户端id：{$request->fd}\n";
    }


    /**
     * 监听消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame)
    {
        echo "客户端发送的数据: {$frame->data}\n";
        $pushData = date("Y-m-d H:i:s");
        $ws->push($frame->fd, "服务端推送的数据: {$pushData}");
    }

    /**
     * 监听关闭事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd)
    {
        echo "客户端：{$fd} 关闭了连接\n";
    }
}

$ws = new Ws();