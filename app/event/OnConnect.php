<?php
declare(strict_types=1);

namespace app\event;
/**
 * 新设备连接事件
 */
class OnConnect
{
    function handle($serv, $fd) {
        echo "Client: Connect. -$fd\n";
    }
}
