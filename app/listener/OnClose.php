<?php
declare(strict_types=1);

namespace app\listener;
/**
 * 新设备连接事件
 */
class OnClose
{
    //事件处理方法
    function handle($serv, $fd)
    {
        echo "Client: Close.\n";
    }
}
