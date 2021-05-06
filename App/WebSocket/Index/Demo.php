<?php
namespace App\WebSocket\Index;

use App\WebSocket\Base;
use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * 演示控制器
 */
class Demo extends Base
{
    //测试
    public function test()
    {
//        $args = $this->caller()->getArgs();
//        $response = $this->response();

        $this->withData(0, 'success');
    }

}
