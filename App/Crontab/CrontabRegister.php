<?php
namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\Component\Process\AbstractProcess;
/**
 * 定时任务注册方法
 * 
 */

class CrontabRegister
{
    //注册开启的定时任务
    protected $className = [
        //Examples::class,
        
    ];

    function __construct()
    {
        //批量注册任务
        foreach ($this->className as $c) {
            Crontab::getInstance()->addTask($c);
        }

        //定义其他任务

    }

}
