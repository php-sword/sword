<?php
/**
 * 应用的配置信息
 * 通过config('app.xx')获取
 */
return [
    // 系统时区
    'timezone' => 'Asia/Shanghai',
    // 启用调试模式
    'debug' => true,
    // 应用名 -保证唯一性，避免在同一机器上运行相同的应用
    'app_name' => 'school',
    // 服务器域名
    'host' => 'xy.kyour.cn',

    //===========功能配置===========

    //是否启用WebSocket
    'enable_ws' => false,
    //是否启用热重载，Http控制器有效
    'hot_reload' => false,
    //Http上传最大值,单位MB
    'http_upload' => 50,

    //===========其他配置===========

];
