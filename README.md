# PHP-Sword

[![Latest Stable Version](https://poser.pugx.org/php-sword/sword/v)](//packagist.org/packages/php-sword/sword) 
[![Total Downloads](https://poser.pugx.org/php-sword/sword/downloads)](//packagist.org/packages/php-sword/sword) 
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.3-8892BF.svg)](http://www.php.net/) 
[![License](https://poser.pugx.org/php-sword/sword/license)](//packagist.org/packages/php-sword/sword)

```
   _____                      _  
  / ____|                    | |  PHP      v7.4.21
 | (_____      _____  _ __ __| |  Swoole   v4.7.1
  \___ \ \ /\ / / _ \| '__/ _` |  Temp Dir /www/dev/Temp
  ____) \ V  V | (_) | | | (_| |  Log Dir  /www/dev/Temp/Log
 |_____/ \_/\_/ \___/|_|  \__,_|  Based EasySwoole v3.4.6
 ------------------------v0.1.12------------------------
```
> 基于EasySwoole的PHP协程快速开发框架，让你更专注于业务代码的开发

## 主要特性

* 采用`PHP7`强类型（严格模式）
* 基于Swoole常驻内存
* 更快速的上手Swoole开发
* 清晰的项目结构
* 提供更多便捷的工具
* 更优的静态资源处理

## 安装
- 安装Swoole扩展，已安装的跳过此步骤
  
访问swoole官网文档 
 [wiki.swoole.com](https://wiki.swoole.com/#/environment)
根据文档进行操作
  
- 安装Composer，已安装的跳过此步骤

通过如下命令下载Composer：

```shell
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

- 项目构建安装：
```shell
composer create-project php-sword/sword sword
```

- 如果已经安装并需要更新，项目根目录执行命令：
```shell
composer update php-sword/framework
```

## 启动项目
```shell
php sword server start
```

守护进程（后台运行）:
```shell
php sword server start -d
```

停止运行：
```shell
php sword server stop
```

更多详细文档: [http://sword.kyour.cn/doc](http://sword.kyour.cn/doc)

## 项目结构
```
PATH  部署目录
├─App           应用目录
│  ├─HttpController      Http控制器
│  ├─WebSocket           WebSocket控制器
│  ├─Crontab             定时任务
│  ├─Process             自定义进程
│  ├─Common              公共方法类
│  ├─Model               ORM模型
│  └─helper.php          全局公共函数
├─Config              配置文件目录
│  ├─app.php              应用配置
│  ├─database.php         数据库配置
│  ├─redis.php            redis服务配置
│  ├─session.php          session配置
│  ├─view.php             视图渲染配置
│  └─xxx.php              更多自定义配置
├─Public         Web静态资源目录
├─Temp           临时数据、缓存、日志
├─vendor         Composer包
├─bootstrap.php  bootstrap事件
├─composer.json  Composer包配置信息
├─dev.php        Easyswoole配置信息
├─EasySwooleEvent.php   Easyswoole事件
├─nginx_make.php    Nginx配置生成工具
└─sword             命令行入口
```

## 更新记录

[转到文档查看](https://github.com/php-sword/sword/wiki/Update)

## 参与开发

> 直接提交PR或者Issue即可

## 版权信息

本项目遵循Apache2.0 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

更多细节参阅 [LICENSE](LICENSE)
