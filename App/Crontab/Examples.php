<?php
namespace App\Crontab;

use EasySwoole\Crontab\JobInterface;

/**
 * 定时任务示例
 * Class Examples
 * @package App\Crontab
 */
class Examples implements JobInterface
{

    /**
     * 是否启用该任务
     * @var bool
     */
    const enable = false;

    public function crontabRule(): string
    {
        // 每分钟
        return '* * * * *';
    }

    public function jobName(): string
    {
        return  __CLASS__;
    }

    /**
     * 定时任务入口
     */
    public function run()
    {
        echo __METHOD__."\n";
    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     */
    public function onException(\Throwable $throwable)
    {
        \Sword\Log::get()->error(__METHOD__ . ' '.$throwable->getMessage());
    }
}