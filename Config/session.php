<?php
/**
 * Session服务配置
 */
return [
    'enable'      => true, //启用Session
    'type'        => 'file', //储存驱动 redis、file
    'session_name' => 'sessionId', //Session的CookieName
    'expire'      => 3600 * 24, //过期时间 s
    //SessionId获取优先级：header>参数>cookie
    'enable_header' => false, //是否支持Header中获取SessionID
    'enable_param' => false //是否支持从请求参数中获取SessionID
];
