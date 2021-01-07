# PHP-Sword

[![Latest Stable Version](https://poser.pugx.org/php-sword/sword/v)](//packagist.org/packages/php-sword/sword) [![Total Downloads](https://poser.pugx.org/php-sword/sword/downloads)](//packagist.org/packages/php-sword/sword) [![PHP Version](https://img.shields.io/badge/php-%3E%3D7.1-8892BF.svg)](http://www.php.net/) [![License](https://poser.pugx.org/php-sword/sword/license)](//packagist.org/packages/php-sword/sword)

> 基于EasySwoole的PHP常驻内存开发框架

## 主要特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 基于Swoole常驻内存
* 协程TCP、UDP、WEB_SOCKET 服务端

## 安装
安装Composer，请先确保正确安装php并配置了环境变量。

>通过执行 `php -v` 命令，如果出现版本号表示安装正常

再通过以下命令下载composer：

```shell
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

框架全新安装：
```shell
composer create-project php-sword/sword sword *
```
> 可以将 `sword *` 换成你的项目名和版本号，如 `sw-obj 0.*`

如果已经安装并需要更新，切换至项目更目录执行下面的命令：
```shell
composer update php-sword/sword
```

## 文档

开发文档: [http://sword.kyour.cn/doc](http://sword.kyour.cn/doc)


## 项目结构
```
PATH  部署目录
├─App           应用目录
│  ├─HttpController      Http控制器目录
│  ├─WebSocket           WebSocket控制器目录
│  ├─Crontab             定时器、计划任务
│  ├─Common              其他公共类
│  ├─Model               模型目录
│  ├─Views               html视图目录
│  └─helper.php          公共函数文件
├─Config              配置文件目录
│  ├─app.php              应用配置
│  ├─database.php         数据库配置
│  ├─redis.php            redis服务器配置
│  ├─session.php            redis服务器配置
│  └─xxx.php              更多自定义配置
├─Public         Web静态资源目录
├─Temp           临时信息、缓存目录
├─vendor         PHP包源码目录
├─bootstrap.php  bootstrap事件
├─composer.json  Composer包配置信息
├─dev.php        服务器配置信息
├─EasySwooleEvent.php    服务器事件处理
├─nginx_make.php    Nginx代理生成工具
├─sword             快捷启动可执行文件
├─...
```

## 更新记录

[转到文档查看](https://github.com/php-sword/sword/wiki/Update)

## 参与开发

> 直接提交PR或者Issue即可

## 版权信息

本项目遵循Apache2.0 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

更多细节参阅 [LICENSE.txt](LICENSE.txt)