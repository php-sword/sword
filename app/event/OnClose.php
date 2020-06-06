<?php
declare(strict_types=1);

namespace app\event;
/**
 * 新设备连接事件
 */
class OnClose
{
    function handle($serv, $fd) {
        echo "Client: Close.\n";
    }
}
