<?php declare(strict_types=1);
namespace App\HttpController\Index;


use App\HttpController\BaseController;

class Index extends BaseController
{

    public function index()
    {
        //直接输出
        $this->response()->write('hello php-sword!');
    }

    public function jsonData()
    {
        //读取静态文件返回
        $this->withData(0, 'hello world!', [
            'tip' => 'json data'
        ]);
    }

    public function html()
    {
        //读取静态文件返回
        $this->fetch('index', [], 'raw');
    }

}
