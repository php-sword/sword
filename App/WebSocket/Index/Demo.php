<?php
namespace App\WebSocket\Index;

use App\WebSocket\BaseController;
use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * 演示控制器
 */
class Demo extends BaseController
{
    //测试
    public function test()
    {
//        $args = $this->caller()->getArgs();
//        $response = $this->response();

        $this->withData(0, 'success');
    }

}
