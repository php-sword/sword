<?php
declare(strict_types=1);

namespace app;
use app\utils\Redis;

use sethink\swooleOrm\Db;
use sethink\swooleOrm\MysqlPool;

class App
{

    protected $MysqlPool;
    const RUNTIME_POOL = APP_PATH.'/runtime/server.tmp';

    // 构造函数
    function __construct()
    {

        $this->init();

    }

    //初始化
    private function init()
    {

        global $argv;

        //判断命令行
        if(isset($argv[1])){
            if($argv[1] == "start"){

                file_put_contents(self::RUNTIME_POOL,'start');

                $serv = new \Swoole\Server("0.0.0.0", 8103); 

                $serv->set([
                    //启用结束符分包协议
                    'open_eof_check' => true,
                    //设定结束符
                    'package_eof' => "\r\n",
                    //守护进程
                    'daemonize' => (isset($argv[2]) and $argv[2] == '-d')?1:0,
                    //日志文件
                    'log_file' => APP_PATH.'/runtime/log.txt'
                ]);

                $serv->on("Start", [$this, 'onStart']);

                $serv->on("WorkerStart", [$this, 'onWorkerStart']);

                //监听连接进入事件
                $serv->on('Connect', '\\app\\event\\OnConnect::handle');

                //监听数据接收事件
                $serv->on('Receive', '\\app\\event\\OnReceive::handle');

                //监听连接关闭事件
                $serv->on('Close', '\\app\\event\\OnClose::handle');

                //启动服务器
                $serv->start();

            }elseif($argv[1] == "stop"){

                echo "Stopping.";
                
                $rp = file_get_contents(self::RUNTIME_POOL);
                if($rp == 'stop'){
                    echo "Stopping, cannot repeat operation.";
                }
                file_put_contents(self::RUNTIME_POOL,'stop');
                
                \Swoole\Timer::tick(1000, function(){

                    //监听信道，是否关闭
                    $rp = file_get_contents(self::RUNTIME_POOL);
                    if($rp == ''){
                        echo "\nBye.\n";
                        \Swoole\Timer::clearAll();
                    }
                    echo ".";
                });

            }
        }else{
            echo "Parameter cannot be empty, you can enter（start,stop）\n";
            die;
        }

    }

    public function onStart($server)
    {
        echo "Server Started.\n";

        //开启定时任务
        \Swoole\Timer::tick(2000, function() use($server){

            //监听信道，是否关闭
            $rp = file_get_contents(self::RUNTIME_POOL);

            if($rp == 'stop'){
                file_put_contents(self::RUNTIME_POOL,'');
                echo "Bye.\n";
                $server->stop();
            }
        });
    }

    public function onWorkerStart($server, $worker_id)
    {
        //redis连接
        $conf = [ 
            'RA' => ['127.0.0.1',6379]   //定义Redis配置
        ];

        Redis::addServer($conf); //添加Redis配置

        // Redis::set('user','private');
        // echo Redis::get('user');

        //MySQL连接
        $config = [
            'host'      => '127.0.0.1', //服务器地址
            'port'      => 3306,    //端口
            'user'      => 'root',  //用户名
            'password'  => '168168',  //密码
            'charset'   => 'utf8',  //编码
            'database'  => 'yunan',  //数据库名
            'prefix'    => 'jz_',  //表前缀
            'poolMin'   => 5, //空闲时，保存的最大链接，默认为5
            'poolMax'   => 1000,    //地址池最大连接数，默认1000
            'clearTime' => 60000, //清除空闲链接定时器，默认60秒，单位ms
            'clearAll'  => 300000,  //空闲多久清空所有连接，默认5分钟，单位ms
            'setDefer'  => true,     //设置是否返回结果,默认为true,
        ];
        $this->MysqlPool = new MysqlPool($config);
        unset($config);

        //执行定时器
        $this->MysqlPool->clearTimer($server);
        
        // $rs = Db::init($this->MysqlPool)
        //     ->name('user')
        //     ->field('uid,phone')
        //     ->find();
        //     echo "\n";
        // echo (json_encode($rs));
    }
}
