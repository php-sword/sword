<?php
namespace EasySwoole\EasySwoole;

use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Component\Di;
use Sword\SwordEvent;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        SwordEvent::initialize();

        // 注册HTTP异常处理
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [\App\Common\ExceptionHandler::class, 'handle']);
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        SwordEvent::mainServerCreate($register);

        /**
         * **************** Crontab任务计划 **********************
         */
        new \App\Crontab\CrontabRegister();
    }
}
