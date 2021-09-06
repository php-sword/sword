<?php
namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

/**
 * 定时任务示例
 * Class Examples
 * @package App\Crontab
 */
class Examples extends AbstractCronTask
{

    /**
     * 是否启用该任务
     * @var bool
     */
    const enable = false;

    public static function getRule(): string
    {
        // 每分钟
        return '* * * * *';
    }

    public static function getTaskName(): string
    {
        return  __CLASS__;
    }

    /**
     * 定时任务入口
     * @param int $taskId
     * @param int $workerIndex
     */
    public function run(int $taskId, int $workerIndex)
    {
        echo __METHOD__."\n";
    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     * @param int $taskId
     * @param int $workerIndex
     */
    public function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        \Sword\Log::get()->error(__METHOD__ . ' '.$throwable->getMessage());
    }
}