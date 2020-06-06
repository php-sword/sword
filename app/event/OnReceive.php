<?php
declare(strict_types=1);

namespace app\event;
/**
 * 新消息处理事件
 */
class OnReceive
{
    function handle($serv, $fd, $from_id, $data) {
        // $serv->send($fd, $data);

        //设备心跳
        if($data == "xt\r\n"){
            //不做任何操作
            return;
        }

        //验证协议
        $Pool = substr($data,0,4);
        if($Pool != "CSIP"){
            //协议头不正确
            return;
        }

        $head = substr($data,0,14);
        echo "\n协议头部".$head."\n";

        //取出功能码
        $cmd = substr($data,4,4);

        //根据协议码做出不同操作
        switch ($cmd) {
            //语音文件
            case '001A':
                //取出连接列表数组
                $conn_list = $serv->getClientList(0, 10);

                //遍历数组发送数据
                foreach ($conn_list as $fd) {
                    $serv->send($fd, $data);
                }
                break;
            //其他
            default:
                
                break;
        }

        $audio = substr($data,14);
        echo "\n收到数据：".(strlen($audio) -2) ."\n";

        // echo "\n收到数据全部：".strlen($data)."\n".$data;

    }
}
