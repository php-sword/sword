<?php
/**
 * EasySwoole框架配置信息
 *
 */
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 8100,
        //服务类型 可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SERVER_TYPE' => config('app.enable_ws')?EASYSWOOLE_WEB_SOCKET_SERVER:EASYSWOOLE_WEB_SERVER,
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,

        // Swoole配置信息
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=> 3,

            // (可选参数）使用 http 上传大文件时可以进行配置
            'package_max_length' => config('app.http_upload') * 1024 * 1024, // 即 100 M
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    'TEMP_DIR' => EASYSWOOLE_ROOT.'/Temp',
    "LOG" => [
        // 设置记录日志文件时日志文件存放目录
        'dir' => EASYSWOOLE_ROOT.'/Temp/Log',
        // 设置记录日志时的日志最低等级，低于此等级的日志不进行记录和显示
        'level' => \EasySwoole\Log\LoggerInterface::LOG_LEVEL_DEBUG,
        // 设置日志处理器 `handler` (handler)
        'handler' => null,
        // 设置开启控制台日志记录到日志文件
        'logConsole' => true,
        // 设置开启在控制台显示日志
        'displayConsole'=>true,
        // 设置打印日志时忽略哪些分类的日志不进行记录
        'ignoreCategory' => []
    ]
];
