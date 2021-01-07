<?php declare(strict_types=1);
namespace App\HttpController\Index;

// use App\Common\Utils;
use App\Model\Identity;
use EasySwoole\Validate\Validate;

class Index extends BaseAuth
{
    public $authRule = [];

    //项目首页
    public function index()
    {
        //读取静态文件返回
        $this->fetch('index', [], 'raw');
    }

}
