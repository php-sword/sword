<?php

$pid_file = "./app.pid";
if(isset($argv[1])){
    if($argv[1] == "start"){

        if(file_exists($pid_file)){
            echo "已经启动，请先结束进程\n";
            return;
        }

        $serv = new \Swoole\Server("0.0.0.0", 8104); 

        $serv->set([
            //启用结束符分包协议
            'open_eof_check' => true,
            //设定结束符
            'package_eof' => "\r\n",
            'daemonize' => 1,
            'pid_file' => './server.pid',
        ]);

        //监听连接进入事件
        $serv->on('Connect', function(){});

        //监听数据接收事件
        $serv->on('Receive', function(){});

        //监听连接关闭事件
        $serv->on('Close', function(){});

        // $serv->on('start', function ($server) use($pid_file){
        //     $pid = $server->master_pid;
        //     echo "\nPid".$pid."\n";
        //     file_put_contents($pid_file, $pid);
        // });

        //启动服务器
        $serv->start();

    }elseif($argv[1] == "stop"){
        $pid = file_get_contents($pid_file);

        echo "\nPid:".$pid."\n";

        sleep(1);
        unlink($pid_file);

        \swoole_process::kill($pid);
    }
}

// print_r($argc);
// echo "\n";
// print_r($argv);

// echo "\nEnd\n";


