<?php
namespace App\WebSocket\Index;

use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;

/**
 * Class Index
 * @package App\WebSocket\Index
 */
class Index extends Controller
{
    //查询我的信息
    public function getInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $base->withData(0, 'success',['data' => 'hello world!']);
    }

}
