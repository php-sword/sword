<?php
namespace EasySwoole\EasySwoole;

use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Socket\Dispatcher;
use App\WebSocket\WebSocketParser;

use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\Db\Config as DbConfig;
use EasySwoole\Redis\Config\RedisConfig;

use EasySwoole\Template\Render;
use App\Common\TemplateRender;
use App\Common\ExceptionHandler;

use EasySwoole\Session\Session;
use EasySwoole\Session\SessionFileHandler;
use App\Common\SessionRedisHandler;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\Manager;
use Sword\Storage\Storage;
use Sword\Storage\StorageException;
use Sword\SwordEvent;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.

        //触发sword事件
        SwordEvent::initialize();

        // -------------------- HTTP --------------------
        //onRequest事件
        Di::getInstance()->set(SysConst::HTTP_GLOBAL_ON_REQUEST,function (Request $request,Response $response){

            //启用Session会话
            $session_conf = config('session');
            $cookie = $request->getCookieParams($session_conf['sessionName']);
            if(empty($cookie)){
                $sid = Session::getInstance()->sessionId();
                $response->setCookie($session_conf['sessionName'], $sid, time() + $session_conf['expire']);
            }else{
                Session::getInstance()->sessionId($cookie);
            }
            return true;
        });

        // 注册异常处理
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class,'handle']);
        // -------------------- HTTP END --------------------

    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        //触发sword事件
        SwordEvent::mainServerCreate();

        /**
         * **************** Crontab任务计划 **********************
         */
        Manager::getInstance()->addProcess(new \App\Crontab\Rergister());

    }
}
