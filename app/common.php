<?php
declare(strict_types=1);
/**
 * This file is part of Sword.
 * @link     http://sword.kyour.cn
 * @document http://sword.kyour.cn/doc
 * @contact  kyour@vip.qq.com
 * @license  http://github.com/php-sword/sword/blob/master/LICENSE
 */

use sword\Redis;
/**
 * 
 */
function djson($status, $msg, $data = []){
    return json_encode([
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ],JSON_UNESCAPED_UNICODE);;
}

function session($fd, $data = null){
    if($data == null){
        $ret = Redis::get('sword_user_'.$fd);
        return $ret == null?null:unserialize($ret);
    }
    
    Redis::set('sword_user_'.$fd,serialize($data));
}