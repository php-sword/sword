<?php
/**
 * Session服务配置
 */
return [
    'enable'      => true, //启用Session
    'type'        => 'file', //储存驱动 redis、file
    'session_name' => 'sessionId', //Session的CookieName
    'expire'      => 86400 * 7 //过期时间 s
];
