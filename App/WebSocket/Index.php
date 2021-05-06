<?php
namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * Class Index
 *
 * 此类是默认的 websocket 消息解析后访问的 控制器
 *
 * @package App\WebSocket
 */
class Index extends Controller
{

    //心跳处理
    public function heart()
    {

    }

}
