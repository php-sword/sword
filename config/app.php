<?php

return [
    //监听地址
    'host' => "0.0.0.0",
    //监听端口
    'port' => 8103,

    //监听事件
    'listener' => [
        'Connect' => [\app\listener\OnConnect::class, "handle"],
        'Receive' => [\app\listener\OnReceive::class, "handle"],
        'Close'   => [\app\listener\OnClose::class,   "handle"]
    ],

    //swoole的set参数
    'swoole_set' => [

        //启用结束符分包协议
        'open_eof_check' => true,
        //设定结束符
        'package_eof' => "\r\n",
        //日志文件
        // 'log_file' => APP_PATH.'/runtime/log.txt',

        //n秒没有数据传输就进行检测
        'tcp_keepidle' => 5,
        //n秒探测一次
        'tcp_keepinterval' => 5,
        //探测的次数，超n次后还没回包close此连接
        'tcp_keepcount' => 2
    ],

    'userFd' => 'im_user_binfd_'
];