<?php
namespace App\Crontab;

use Sword\Component\Crontab\Register;

/**
 * 定时任务注册方法
 */
class CrontabRegister extends Register
{
    /**
     * 注册开启的定时任务类
     * 请使用字符串或 Examples::class 的方式
     * @var array[string]
     */
    protected $className = [
        //Examples::class,
        
    ];

    /**
     * 其他Task创建
     */
    public function taskCreate()
    {

    }

}
