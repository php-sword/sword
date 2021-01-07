<?php
namespace App\WebSocket\Index;

use App\Model\Article;
use App\Model\UserFollow;
use EasySwoole\Socket\AbstractInterface\Controller;

use App\WebSocket\Base;
use EasySwoole\Mysqli\QueryBuilder;

use App\Model\User as UserModel;

/**
 * 前端帖子相关
 */
class User extends Controller
{
    //查询我的信息
    public function getInfo()
    {
        $args = $this->caller()->getArgs();
        $base = new Base($args, $this->response());

        $base->withData(0, 'success',['data' => 'hello wlord!']);
    }

}
