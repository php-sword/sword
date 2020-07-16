<?php
declare(strict_types=1);
/**
 * This file is part of Sword.
 * @link     http://sword.kyour.cn
 * @document http://sword.kyour.cn/doc
 * @contact  kyour@vip.qq.com
 * @license  http://github.com/php-sword/sword/blob/master/LICENSE
 */

namespace sword;

//自动加载
require __DIR__ . '/vendor/autoload.php';

define('APP_PATH',__DIR__);

//实例化应用
$app = new App();

//启动应用
$app->run();
