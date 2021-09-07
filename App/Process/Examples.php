<?php
namespace App\Process;

use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Process;

/**
 * 自定义进程示例
 * Class Examples
 * @package App\Crontab
 */
class Examples extends AbstractProcess
{
    /**
     * 是否启用该进程
     * @var bool
     */
    const enable = false;

    /**
     * 进程入口
     * @param $arg
     */
    protected function run($arg)
    {
        echo __METHOD__."\n";
    }

    /**
     * 当主进程对子进程发送消息的时候 会触发
     * @param Process $process
     */
    protected function onPipeReadable(Process $process)
    {
//        $recvMsgFromMain = $process->read(); // 用于获取主进程给当前进程发送的消息
//        var_dump('收到主进程发送的消息: ');
//        var_dump($recvMsgFromMain);
    }

    /**
     * 捕获 run 方法内抛出的异常
     * @param \Throwable $throwable
     * @param ...$args
     */
    protected function onException(\Throwable $throwable, ...$args)
    {
        \Sword\Log::get()->error(__METHOD__ . $throwable->getMessage());
    }

    /**
     * 进程意外退出 触发此回调
     */
    protected function onShutDown()
    {
    }

    /**
     * 当进程接收到 SIGTERM 信号触发该回调
     */
    protected function onSigTerm()
    {
    }
}