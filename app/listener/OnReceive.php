<?php
declare(strict_types=1);

namespace app\listener;

use app\packer\CsipPacker;
use app\controller\Csip;
/**
 * 新消息处理事件
 */
class OnReceive
{
    //事件处理方法
    function handle($server, $fd, $reactorId, $data)
    {

        //设备心跳
        if($data == "xt\r\n"){
            //不做任何操作
            return;
        }

        if(CsipPacker::verify($data)){
            //是csip协议
            $csip = new CsipPacker();
            $csip->decode($data);

            (new Csip())->handle($server,$fd,$csip);

            //取出连接列表数组
            // $conn_list = $server->getClientList(0, 10);
            
            // print_r($csip);

            // //遍历数组发送数据
            // foreach ($conn_list as $_fd) {
            //     $server->send($_fd, $data);
            // }

            // echo strlen($data)."\n";

            return;
        }else{
            
            //协议头不正确
            echo "未定义协议：".$data."\n";
            
            $arr = explode("\n",$data);
            
            if(strstr($arr[0], 'HTTP/')){
                //取得headers
                $headers = [];
                foreach($arr as $v){
                    $obj = explode(":",$v);
                    if(isset($obj[1])){
                        $headers[$obj[0]] = trim($obj[1]);
                    }
                }
                
                var_dump($headers);
                
                //判断是否升级为websocket
                if($headers['Upgrade'] == "websocket" and $headers['Connection'] == "Upgrade"){
                    
                    $magic = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
                    
                    $key = $headers['Sec-WebSocket-Key'] + $magic;
                    
                    $key = base64_encode(sha1($key));
                    
                    //返回消息体
                    $back = 'HTTP/1.1 101 Switching Protocols
Upgrade: websocket
Connection: Upgrade
Sec-WebSocket-Accept: '.$key;

echo $back;
                    $serv->send($fd, $back);
// Sec-WebSocket-Protocol: chat';
                    echo "是WebSocket";
                }
                
            }else{
                
                print_r($arr);
            }
            
            
            //验证是否为ws连接
            // $serv->send($fd, "ok");
            

            // $serv->send($fd, "not pool\r\n");
            return;
        }

/*
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
*/
    }
}
