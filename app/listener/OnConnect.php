<?php
declare(strict_types=1);

namespace app\listener;

use sword\orm\Db;
/**
 * 新设备连接事件
 */
class OnConnect
{
    //事件处理方法
    function handle($serv, $fd)
    {
        echo "Client: Connect. -$fd\n";
        // $data = Db::name('user')
        //     ->field('uid,phone')
        //     ->where(['uid','<',10])
        //     ->select();
        // print_r($data);
    }
}
